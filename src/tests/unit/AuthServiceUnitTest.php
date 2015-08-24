<?php
namespace tests\unit;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\entities\User;
use EuroMillions\services\AuthService;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\RememberToken;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;

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

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            self::ENTITIES_NAMESPACE . 'User' => $this->userRepository_double
        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->prophesize('EuroMillions\repositories\UserRepository');
        $this->hasher_double = $this->prophesize('EuroMillions\interfaces\IPasswordHasher');
        $this->storageStrategy_double = $this->prophesize('EuroMillions\interfaces\IAuthStorageStrategy');
        $this->urlManager_double = $this->prophesize('EuroMillions\interfaces\IUrlManager');
        $this->logService_double = $this->prophesize('EuroMillions\services\LogService');
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
        $this->storageStrategy_double->storeRemember(Argument::type('EuroMillions\interfaces\IUser'))->shouldBeCalled();
        $this->storageStrategy_double->setCurrentUserId(Argument::type('EuroMillions\vo\UserId'))->shouldBeCalled();
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
        $this->storageStrategy_double->setCurrentUserId(Argument::type('EuroMillions\vo\UserId'))->shouldBeCalled();

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
        $actual = $sut->check($credentials, self::USER_AGENT);
        return $actual;
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
        $user_mock = $this->prophesize('EuroMillions\entities\User');
        $user_mock->getId()->willReturn($this->prophesize('EuroMillions\vo\UserId'));
        $user_mock->getRememberToken()->willReturn($this->prophesize('EuroMillions\vo\RememberToken'));
        $password_stub = $this->prophesize('EuroMillions\vo\Password');
        $password_stub->password()->willReturn(self::HASH);
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
     * should returnProperErrorMessage
     */
    public function test_register_calledWithExistingEmail_returnProperErrorMessage()
    {
        $existing_mail = 'antonio.hernandez@panamedia.net';
        $this->userRepository_double->getByEmail($existing_mail)->willReturn(new User());
        $credentials = $this->getRegisterCredentials($existing_mail);
        $expected = new ServiceActionResult(false, 'Email already registered');
        $sut = $this->getSut();
        $actual = $sut->register($credentials);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method register
     * when calledWithProperCredentials
     * should storeNewUserAndLoginAndReturnOk
     */
    public function test_register_calledWithProperCredentials_storeNewUserAndLoginAndReturnOk()
    {
        $this->expectFlushInEntityManager();
        $credentials = $this->getRegisterCredentials();
        $user = new User();
        $user->initialize(
            [
                'name'     => $credentials['name'],
                'surname'  => $credentials['surname'],
                'email'    => new Email($credentials['email']),
                'password' => new Password($credentials['password'], $this->hasher_double->reveal()),
                'country'  => $credentials['country'],
                'balance'  => new Money(0, new Currency('EUR')),
            ]
        );
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn(null);
        $this->userRepository_double->add($user)->shouldBeCalled();
        $this->storageStrategy_double->setCurrentUserId(Argument::type('EuroMillions\vo\UserId'))->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->register($credentials);
        $expected = new ServiceActionResult(true, $user);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getEmailValidationService
     * when called
     * should returnProperValue
     */
    public function test_getEmailValidationService_called_returnProperValue()
    {
        $user = new User();
        $email = 'azofaifo@azofaifo.com';
        $user->initialize(['email' => new Email($email)]);
        $expected = 'sdlkfjds0943¡';
        $emailValidationTokenGenerator = $this->prophesize('EuroMillions\interfaces\IEmailValidationToken');
        $emailValidationTokenGenerator->token($email)->willReturn($expected);
        $sut = $this->getSut();
        $actual = $sut->getEmailValidationToken($user, $emailValidationTokenGenerator->reveal());
        $this->assertEquals($expected, $actual);
    }

    /**
     * method validateEmailToken
     * when calledWithValidationFalse
     * should returnErrorResult
     */
    public function test_validateEmailToken_calledWithValidationFalse_returnFalse()
    {
        $expected = new ServiceActionResult(false, "The token is invalid");
        $token = 'ñaiijlñasdil¡';
        $validation_result = false;
        list($user, $emailValidationTokenGenerator) = $this->getUserAndPrepareValidator($token, $validation_result);
        $actual = $this->exerciseValidateEmailToken($user, $token, $emailValidationTokenGenerator);
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
        $expected_result = new ServiceActionResult(true, $expected_user);
        $this->expectFlushInEntityManager($expected_user);
        $actual = $this->exerciseValidateEmailToken($user, $token, $emailValidationTokenGenerator);
        $this->assertEquals($expected_result, $actual);
    }

    /**
     * @return AuthService
     */
    private function getSut()
    {
        $dsf = $this->getDomainServiceFactory();
        $sut = $dsf->getAuthService($this->hasher_double->reveal(), $this->storageStrategy_double->reveal(), $this->urlManager_double->reveal(), $this->logService_double->reveal());
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
        $actual = $sut->loginWithRememberMe();
        return $actual;
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

        $actual = $this->exerciseLoginWithRememberMe();
        return $actual;
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
        $remember = new RememberToken($user->getEmail()->email(), $user->getPassword()->password(), $user_agent);

        $this->prepareStorageAndRepository($user_id_obj, $user, $user_id, $remember->token());

        $actual = $this->exerciseLoginWithRememberMe();
        return $actual;
    }

    /**
     * @param $email
     * @param $confirm_password
     * @return array
     */
    private function getRegisterCredentials($email = 'nonexisting@email.com', $confirm_password = 'passWord01')
    {
        $credentials = [
            'name'             => 'Antonio',
            'surname'          => 'Hernández',
            'email'            => $email,
            'password'         => 'passWord01',
            'confirm_password' => $confirm_password,
            'country'          => 'Spain',
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
        $emailValidationTokenGenerator = $this->prophesize('EuroMillions\interfaces\IEmailValidationToken');
        $emailValidationTokenGenerator->validate($email, $token)->willReturn($validation_result);
        return array($user, $emailValidationTokenGenerator);
    }

    /**
     * @param $user
     * @param $token
     * @param $emailValidationTokenGenerator
     * @return ServiceActionResult
     */
    private function exerciseValidateEmailToken($user, $token, $emailValidationTokenGenerator)
    {
        $sut = $this->getSut();
        $actual = $sut->validateEmailToken($user, $token, $emailValidationTokenGenerator->reveal());
        return $actual;
    }
}