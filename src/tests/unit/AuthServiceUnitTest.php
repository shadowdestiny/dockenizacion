<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\RememberToken;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\EmailMother;
use EuroMillions\tests\helpers\mothers\UserMother;

class AuthServiceUnitTest extends UnitTestBase
{
    const HASH = 'azofaifahash';
    const EMAIL = 'hola@azofaifa.com';
    /** @var UserId */
    private $userId;
    const USERNAME = 'azofaifa';
    const PASS = 'azofaifaPass01';
    const USER_AGENT = 'Nocilla correfocs 66.6';
    private $userRepository_double;
    private $hasher_double;
    private $storageStrategy_double;
    private $urlManager_double;
    private $logService_double;
    private $emailService_double;
    private $userService_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double
        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->hasher_double = $this->getInterfaceWebDouble('IPasswordHasher');
        $this->hasher_double->hashPassword(Argument::any())->willReturn(self::HASH);
        $this->storageStrategy_double = $this->getInterfaceWebDouble('IAuthStorageStrategy');
        $this->urlManager_double = $this->getInterfaceDouble('IUrlManager');
        $this->logService_double = $this->getServiceDouble('LogService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->userId = UserId::create();
        parent::setUp();
    }

    /**
     * method check
     * when calledWithRightCredentials
     * should returnTrue
     */
    public function test_check_calledWithRightCredentials_returnTrue()
    {
        $credentials = $this->prepareHasherCredentialsAndUserRepo(false);

        $actual = $this->exerciseCheck($credentials);

        $this->assertTrue($actual);
    }

    /**
     * method check
     * when calledWithRememberAndPasswordMatch
     * should createRememberEnvironment
     */
    public function test_check_calledWithRememberAndPasswordMatch_createRememberEnvironment()
    {
        /** @var User $user */
        $credentials = $this->prepareHasherCredentialsAndUserRepo(true);

        $this->storageStrategy_double->storeRemember(Argument::type($this->getInterfacesToArgument('IUser')))->shouldBeCalled();
        $this->storageStrategy_double->setCurrentUserId(Argument::type($this->getVOToArgument('UserId')))->shouldBeCalled();
        $this->expectFlushInEntityManager();
        $this->exerciseCheck($credentials);
    }

    /**
     * method check
     * when calledWithRemeberAndPasswordDontMatch
     * should notStoreToken
     */
    public function test_check_calledWithRemeberAndPasswordDontMatch_notStoreToken()
    {
        $credentials = $this->prepareHasherAndCredentials(true, false);

        $user_mock = $this->prepareUserMock();
        $user_mock->setRememberToken(self::USER_AGENT)->shouldNotBeCalled();

        $this->userRepository_double->getByEmail(self::EMAIL)->willReturn($user_mock);
        $this->storageStrategy_double->storeRemember(Argument::any())->shouldNotBeCalled();
        $this->storageStrategy_double->setCurrentUserId(Argument::any())->shouldNotBeCalled();

        $this->exerciseCheck($credentials);
    }

    /**
     * method check
     * when calledWithoutRememberAndPasswordMatch
     * should notSetRememberEnvironmentButSetCurrentUser
     */
    public function test_check_calledWithoutRememberAndPasswordMatch_notSetRememberEnvironmentButSetCurrentUser()
    {
        $credentials = $this->prepareHasherAndCredentials(false, true);

        $user_mock = $this->prepareUserMock();
        $user_mock->setRememberToken(self::USER_AGENT)->shouldNotBeCalled();

        $this->userRepository_double->getByEmail(self::EMAIL)->willReturn($user_mock);

        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);

        $this->storageStrategy_double->storeRemember(Argument::any())->shouldNotBeCalled();
        $this->storageStrategy_double->setCurrentUserId(Argument::type($this->getVOToArgument('UserId')))->shouldBeCalled();

        $this->exerciseCheck($credentials);
    }

    /**
     * method check
     * when calledWithWrongEmail
     * should returnFalse
     */
    public function test_check_calledWithWrongEmail_returnFalse()
    {
        $this->userRepository_double->getByEmail(Argument::any())->willReturn(null);
        $actual = $this->exerciseCheck(['email' => 'email@email.com']);
        $this->assertFalse($actual);
    }


    /**
     * method updatePassword
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_updatePassword_called_returnServiceActionResultTrue()
    {
        $expected = new ActionResult(true,'Your password was changed correctly');
        $sut = $this->getSut();
        $user = $this->getNewUser();
        $password = 'passworD01';
        $email = EmailMother::aResetPasswordEmailTemplate();
        $this->userRepository_double->add($user)->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldNotBeCalled();
        $this->emailService_double->sendTransactionalEmail(Argument::any(),$email)->shouldBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $actual = $sut->updatePassword($user,$password);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updatePassword
     * when called
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_updatePassword_called_throwExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ActionResult(false);
        $sut = $this->getSut();
        $user = $this->getNewUser();
        $password = 'passworD01';
        $this->userRepository_double->add($user)->willThrow('\Exception','Error updating password');
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $actual = $sut->updatePassword($user,$password);
        $this->assertEquals($expected,$actual);

    }

    /**
     * @param $remember
     * @param bool $passwordIsGood
     * @return array
     */
    private function prepareHasherCredentialsAndUserRepo($remember, $passwordIsGood = true)
    {
        $credentials = $this->prepareHasherAndCredentials($remember, $passwordIsGood);

        $user = new User();
        $user->initialize([
            'id'       => $this->userId,
            'password' => new Password(self::PASS, $this->hasher_double->reveal()),
            'email'    => new Email(self::EMAIL)
        ]);

        $this->userRepository_double->getByEmail(self::EMAIL)->willReturn($user);
        return $credentials;
    }

    /**
     * @param $credentials
     * @return boolean
     */
    private function exerciseCheck($credentials)
    {
        $sut = $this->getSut();
        return $sut->check($credentials, self::USER_AGENT);
    }

    /**
     * @param $remember
     * @param $passwordIsGood
     * @return array
     */
    private function prepareHasherAndCredentials($remember, $passwordIsGood)
    {
        $credentials = ['email' => self::EMAIL, 'password' => self::PASS, 'remember' => $remember];

        $this->hasher_double->hashPassword(self::PASS)->willReturn(self::HASH);
        $this->hasher_double->checkPassword(self::PASS, self::HASH)->willReturn($passwordIsGood);
        return $credentials;
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    private function prepareUserMock()
    {
        $user_mock = $this->getEntityDouble('User');
        $user_mock->getId()->willReturn($this->getValueObjectDouble('UserId'));
        $user_mock->getRememberToken()->willReturn($this->getValueObjectDouble('RememberToken'));
        $password_stub = $this->getValueObjectDouble('Password');
        $password_stub->toNative()->willReturn(self::HASH);
        $user_mock->getPassword()->willReturn($password_stub);
        return $user_mock;
    }

    private function expectFlushInEntityManager($argument = null)
    {
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($argument)->shouldBeCalled();
        $this->stubEntityManager($entityManager_stub);
    }

    /**
     * method getCurrentUser
     * when called
     * should returnIUserWithTheIdReturnedByTheStrategy
     */
    public function test_getCurrentUser_called_returnIUserWithTheIdReturnedByTheStrategy()
    {
        $expected = UserId::create();
        $this->storageStrategy_double->getCurrentUserId()->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->getCurrentUser();
        $this->assertEquals($expected, $actual->getId());
    }

    /**
     * method isLogged
     * when called
     * should returnFalseIfCurrentUserIsGuestUserAndTrueIfCurrentUserIsUser
     * @dataProvider getRepositoryResultAndExpectedLogged
     * @param $result
     * @param $expected
     */
    public function test_isLogged_called_returnFalseIfCurrentUserIsGuestUserAndTrueIfCurrentUserIsUser($result, $expected)
    {
        $this->storageStrategy_double->getCurrentUserId()->willReturn(UserId::create());
        $this->userRepository_double->find(Argument::any())->willReturn($result);
        $sut = $this->getSut();
        $actual = $sut->isLogged();
        $this->assertEquals($expected, $actual);
    }

    public function getRepositoryResultAndExpectedLogged()
    {
        return [
            [null, false],
            [new User(), true],
        ];
    }

    /**
     * method loginWithRememberMe
     * when tokenDoesntValidate
     * should deleteRememberMeDataAndReturnFalse
     */
    public function test_loginWithRememberMe_tokenDoesntValidate_deleteRememberMeDataAndReturnFalse()
    {
        $this->storageStrategy_double->removeRemember()->shouldBeCalled();
        $actual = $this->exerciseLoginWithRememberMeWithTokenNotValidating();
        $this->assertFalse($actual);
    }

    /**
     * method loginWithRememberMe
     * when userIdIsNotValid
     * should deleteRememberMeDataAndReturnFalse
     */
    public function test_loginWithRememberMe_userIdIsNotValid_deleteRememberMeDataAndReturnFalse()
    {
        $this->prepareUserIdNotValid();
        $this->storageStrategy_double->removeRemember()->shouldBeCalled();
        $actual = $this->exerciseLoginWithRememberMe();
        $this->assertFalse($actual);
    }

    /**
     * method loginWithRememberMe
     * when tokenValidates
     * should createdProperUserSessionAndReturnTrue
     */
    public function test_loginWithRememberMe_tokenValidates_createdProperUserSession()
    {
        $user_id = UserId::create();

        $user_agent = 'azofaifo';
        $user = $this->getUserWithRemember($user_id, $user_agent);
        $this->storageStrategy_double->setCurrentUserId($user_id)->shouldBeCalled();

        $actual = $this->exerciseRememberMeWithTokenValidating($user, $user_agent, $user_id, $user_id->id());
        $this->assertTrue($actual);
    }

    /**
     * method register
     * when calledWithExistingEmail
     * should returnNotSuccess
     */
    public function test_register_calledWithExistingEmail_returnNotSuccess()
    {
        $existing_mail = 'antonio.hernandez@panamedia.net';
        $this->userRepository_double->getByEmail($existing_mail)->willReturn(new User());
        $credentials = $this->getRegisterCredentials($existing_mail);
        $sut = $this->getSut();
        $actual = $sut->register($credentials);
        $this->assertFalse($actual->success());
    }

    /**
     * method register
     * when calledWithProperCredentials
     * should storeNewUserAndLoginAndReturnOk
     */
    public function test_register_calledWithProperCredentials_storeNewUserAndLoginAndReturnOk()
    {
        $this->expectFlushInEntityManager();
        $welcome_email_template = EmailMother::aWelcomeEmailTemplate();
        $credentials = $this->getRegisterCredentials();
        $user = UserMother::aJustRegisteredUser($this->hasher_double->reveal())->build();
        $welcome_email_template->setUser($user);
        $this->userRepository_double->getByEmail(UserBuilder::DEFAULT_EMAIL)->willReturn(null);
        $this->userRepository_double->add($user)->shouldBeCalled();
        $this->storageStrategy_double->setCurrentUserId(Argument::type($this->getVOToArgument('UserId')))->shouldBeCalled();
        $this->urlManager_double->get(Argument::type('string'))->willReturn('http://localhost/validate/441a9e42f0e3c769a6112b56a04b6');
        $this->emailService_double->sendTransactionalEmail($user, $welcome_email_template)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->register($credentials);
        $expected = new ActionResult(true, $user);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method registerFromCheckout
     * when calledWithProperData
     * should storeNewUserAndReturnOk
     */
    public function test_registerFromCheckout_calledWithProperData_storeNewUserAndReturnOk()
    {
        $this->expectFlushInEntityManager();
        $user_id = UserId::create();
        $user = UserMother::aJustRegisteredUser($this->hasher_double->reveal());
        $user_build_entity = $user->build();
        $expected = new ActionResult(true,$user_build_entity);
        $credentials = $this->getRegisterCredentials();
        $user_build_entity->setId($user_id);
        $this->storageStrategy_double->getCurrentUserId()->willReturn($user_id);
        $this->userRepository_double->find($user_id)->willReturn(null);
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn(null);
        $this->userRepository_double->addWithId($user_build_entity)->shouldBeCalled();
        $this->storageStrategy_double->setCurrentUserId($user_id)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->registerFromCheckout($credentials,$user_id);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method registerFromCheckout
     * when calledWithProperData
     * should returnUserEntityWithUserIdPassedAsParameter
     */
    public function test_registerFromCheckout_calledWithProperData_returnUserEntityWithUserIdPassedAsParameter()
    {
        $this->expectFlushInEntityManager();
        $user_id = UserId::create();
        $user = UserMother::aJustRegisteredUser($this->hasher_double->reveal());
        $user_build_entity = $user->build();
        $expected = $user_id;
        $credentials = $this->getRegisterCredentials();
        $user_build_entity->setId($user_id);
        $this->storageStrategy_double->getCurrentUserId()->willReturn($user_id);
        $this->userRepository_double->find($user_id)->willReturn(null);
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn(null);
        $this->userRepository_double->addWithId($user_build_entity)->shouldBeCalled();
        $this->storageStrategy_double->setCurrentUserId($user_id)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->registerFromCheckout($credentials,$user_id);
        $this->assertEquals($expected,$actual->getValues()->getId());
    }


    /**
     * method validateEmailToken
     * when calledWithValidationFalse
     * should returnErrorResult
     */
    public function test_validateEmailToken_calledWithValidationFalse_returnFalse()
    {
        $expected = new ActionResult(false, "The token is invalid");
        $token = 'ñaiijlñasdil¡';
        $validation_result = false;
        list($user, $emailValidationTokenGenerator) = $this->getUserAndPrepareValidator($token, $validation_result);
        $actual = $this->exerciseValidateEmailToken($token, $emailValidationTokenGenerator);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method validateEmailToken
     * when calledWithValidationTrue
     * should returnValidatedUserAndFlush
     */
    public function test_validateEmailToken_calledWithValidationTrue_returnValidatedUserAndFlush()
    {
        $token = 'ñaiijlñasdil¡';
        $validation_result = true;
        list($user, $emailValidationTokenGenerator) = $this->getUserAndPrepareValidator($token, $validation_result);
        /** @var User $expected_user */
        $expected_user = clone $user;
        $expected_user->setValidated(true);
        $expected_result = new ActionResult(true, $expected_user);
        $this->expectFlushInEntityManager($expected_user);
        $actual = $this->exerciseValidateEmailToken($token, $emailValidationTokenGenerator);
        $this->assertEquals($expected_result, $actual);
    }

    /**
     * method forgotPassword
     * when called
     * should returnServiceActionResult
     */
    public function test_forgotPassword_called_returnServiceActionResult()
    {
        $emailUser = 'algarrobo@currojimenez.com';
        $email = new Email($emailUser);
        $user = $this->getNewUser();
        $this->urlManager_double->get(Argument::type('string'))->willReturn('http://localhost/validate/441a9e42f0e3c769a6112b56a04b6');
        $this->userRepository_double->getByEmail($email->toNative())->willReturn($user);
        $sut = $this->getSut();
        $actual = $sut->forgotPassword($email);
        $this->assertTrue($actual->success());
        $this->assertInstanceOf($this->getResultObject('ActionResult'), $actual);
    }

    /**
     * method resetPassword
     * when called
     * should returnServiceActionResult
     */
    public function test_resetPassword_called_returnServiceActionResult()
    {
        $user = $this->getNewUser();
        $token = '33e4e6a08f82abb38566fc3bb8e8ef0d';
        $sut = $this->getSut();
        $actual = $sut->resetPassword($token);
        $this->userRepository_double->getByToken($token)->willReturn($user);
        $this->assertInstanceOf($this->getResultObject('ActionResult'), $actual);
    }


    /**
     * @return AuthService
     */
    private function getSut()
    {
        $sut = new AuthService($this->getEntityManagerRevealed(), $this->hasher_double->reveal(), $this->storageStrategy_double->reveal(), $this->urlManager_double->reveal(), $this->logService_double->reveal(), $this->emailService_double->reveal(), $this->userService_double->reveal());
        return $sut;
    }

    /**
     * @param $user_id_obj
     * @return User
     */
    private function getUser($user_id_obj)
    {
        $user = new User();
        $user->initialize([
            'id'       => $user_id_obj,
            'email'    => new Email('azofaifo@algarrobo.com'),
            'password' => new Password('azofaifoPass01', new NullPasswordHasher())
        ]);
        return $user;
    }

    /**
     * @return User
     */
    private function getNewUser()
    {
        $user = new User();
        $user->initialize(
            [

                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', $this->hasher_double->reveal()),
                'validated' => false,
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    /**
     * @param $user_id_obj
     * @param $user_agent
     * @return User
     */
    private function getUserWithRemember($user_id_obj, $user_agent)
    {
        $user = $this->getUser($user_id_obj);
        $user->setRememberToken($user_agent);
        return $user;
    }

    /**
     * @param $user_id_obj
     * @param $user
     * @param $user_id
     * @param $token
     */
    private function prepareStorageAndRepository($user_id_obj, $user, $user_id, $token)
    {
        $this->userRepository_double->find($user_id_obj)->willReturn($user);
        $this->storageStrategy_double->getRememberUserId()->willReturn($user_id);
        $this->storageStrategy_double->getRememberToken()->willReturn($token);
    }

    /**
     * @return bool
     */
    private function exerciseLoginWithRememberMe()
    {
        $sut = $this->getSut();
        return $sut->loginWithRememberMe();
    }

    /**
     * @return bool
     */
    private function exerciseLoginWithRememberMeWithTokenNotValidating()
    {
        $user_id = UserId::create();
        $user_id_obj = new UserId($user_id);
        $user = $this->getUserWithRemember($user_id_obj, 'azofaifostring');

        $this->prepareStorageAndRepository($user_id_obj, $user, $user_id, 'another_token');

        return $this->exerciseLoginWithRememberMe();
    }

    private function prepareUserIdNotValid()
    {
        $this->storageStrategy_double->getRememberUserId()->willReturn(UserId::create());
        $this->storageStrategy_double->getRememberToken()->willReturn(null);
        $this->userRepository_double->find(Argument::any())->willReturn(null);
    }

    /**
     * @param User $user
     * @param $user_agent
     * @param $user_id_obj
     * @param $user_id
     * @return bool
     */
    private function exerciseRememberMeWithTokenValidating($user, $user_agent, $user_id_obj, $user_id)
    {
        $remember = new RememberToken($user->getEmail()->toNative(), $user->getPassword()->toNative(), $user_agent);

        $this->prepareStorageAndRepository($user_id_obj, $user, $user_id, $remember->toNative());

        return $this->exerciseLoginWithRememberMe();
    }

    /**
     * @param $email
     * @param $confirm_password
     * @return array
     */
    private function getRegisterCredentials(
        $email = UserBuilder::DEFAULT_EMAIL,
        $confirm_password = UserBuilder::DEFAULT_PASSWORD
    )
    {
        $credentials = [
            'name'             => UserBuilder::DEFAULT_NAME,
            'surname'          => UserBuilder::DEFAULT_SURNAME,
            'email'            => $email,
            'password'         => UserBuilder::DEFAULT_PASSWORD,
            'confirm_password' => $confirm_password,
            'country'          => UserBuilder::DEFAULT_COUNTRY,
        ];
        return $credentials;
    }

    /**
     * @param $token
     * @param $validation_result
     * @return array
     */
    private function getUserAndPrepareValidator($token, $validation_result)
    {
        $user = new User();
        $email = 'azofaifo@azofaifo.com';
        $user->initialize(['email' => new Email($email)]);
        $emailValidationTokenGenerator = $this->getInterfaceWebDouble('IEmailValidationToken');
        $this->userRepository_double->getByToken($token)->willReturn($user);
        $emailValidationTokenGenerator->validate($email, $token)->willReturn($validation_result);
        return array($user, $emailValidationTokenGenerator);
    }

    /**
     * @param $user
     * @param $token
     * @param $emailValidationTokenGenerator
     * @return ActionResult
     */
    private function exerciseValidateEmailToken($token, $emailValidationTokenGenerator)
    {
        $sut = $this->getSut();
        $actual = $sut->validateEmailToken($token, $emailValidationTokenGenerator->reveal());
        return $actual;
    }
}