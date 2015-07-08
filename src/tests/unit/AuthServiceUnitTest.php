<?php
namespace tests\unit;

use EuroMillions\entities\User;
use EuroMillions\services\AuthService;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\RememberToken;
use EuroMillions\vo\UserId;
use EuroMillions\vo\Username;
use Prophecy\Argument;
use Rhumsaa\Uuid\Uuid;
use tests\base\UnitTestBase;

class AuthServiceUnitTest extends UnitTestBase
{
    const HASH = 'azofaifahash';
    /** @var UserId */
    private $userId;
    const USERNAME = 'azofaifa';
    const PASS = 'azofaifaPass01';
    const USER_AGENT = 'Nocilla correfocs 66.6';
    protected $userRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            self::ENTITIES_NAMESPACE.'User' => $this->userRepository_double
        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->prophesize('EuroMillions\repositories\UserRepository');
        parent::setUp();
        $this->userId = UserId::create(strtoupper(Uuid::uuid4()));
    }

    /**
     * method check
     * when calledWithRightCredentials
     * should returnTrue
     */
    public function test_check_calledWithRightCredentials_returnTrue()
    {
        list($credentials, $hasher_stub) = $this->prepareHasherCredentialsAndUserRepo(false);

        $cookieManager_dummy = $this->prophesize('EuroMillions\interfaces\ICookieManager');
        $actual = $this->exerciseCheck($hasher_stub, $cookieManager_dummy, $credentials);

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
        list($credentials, $hasher_stub) = $this->prepareHasherCredentialsAndUserRepo(true);
        $cookieManager_mock = $this->prophesize('EuroMillions\interfaces\ICookieManager');
        $rememberToken = new RememberToken(self::USERNAME, self::HASH, self::USER_AGENT);
        $cookieManager_mock->set('RMU', $this->userId->id(), AuthService::REMEMBER_ME_EXPIRATION)->shouldBeCalledTimes(1);
        $cookieManager_mock->set('RMT', $rememberToken->token(), AuthService::REMEMBER_ME_EXPIRATION)->shouldBeCalledTimes(1);
        $this->expectFlushInEntityManager();
        $this->exerciseCheck($hasher_stub, $cookieManager_mock, $credentials);
    }

    /**
     * method check
     * when calledWithRemeberAndPasswordDontMatch
     * should notStoreToken
     */
    public function test_check_calledWithRemeberAndPasswordDontMatch_notStoreToken()
    {
        list($credentials, $hasher_stub) = $this->prepareHasherAndCredentials(true, false);

        $user_mock = $this->prepareUserMock();
        $user_mock->setRememberToken(self::USER_AGENT)->shouldNotBeCalled();

        $this->userRepository_double->getByUsername(self::USERNAME)->willReturn($user_mock);
        $cookieManager_mock = $this->prophesize('EuroMillions\interfaces\ICookieManager');
        $cookieManager_mock->set(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();
        $this->exerciseCheck($hasher_stub, $cookieManager_mock, $credentials);
    }

    /**
     * method check
     * when calledWithRememberAndPasswordMatch
     * should storeToken
     */
    public function test_check_calledWithRememberAndPasswordMatch_storeToken()
    {
        list($credentials, $hasher_stub) = $this->prepareHasherAndCredentials(true, true);

        $user_mock = $this->prepareUserMock();
        $user_mock->setRememberToken(self::USER_AGENT)->shouldBeCalled();

        $this->userRepository_double->getByUsername(self::USERNAME)->willReturn($user_mock);

        $this->expectFlushInEntityManager();

        $cookieManager_mock = $this->prophesize('EuroMillions\interfaces\ICookieManager');
        $cookieManager_mock->set(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();

        $this->exerciseCheck($hasher_stub, $cookieManager_mock, $credentials);
    }

    /**
     * method check
     * when calledWithoutRemember
     * should notSetRememberEnvironment
     */
    public function test_check_calledWithoutRemember_notSetRememberEnvironment()
    {
        list($credentials, $hasher_stub) = $this->prepareHasherAndCredentials(false, true);

        $user_mock = $this->prepareUserMock();
        $user_mock->setRememberToken(self::USER_AGENT)->shouldNotBeCalled();

        $this->userRepository_double->getByUsername(self::USERNAME)->willReturn($user_mock);

        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);

        $cookieManager_mock = $this->prophesize('EuroMillions\interfaces\ICookieManager');
        $cookieManager_mock->set(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->exerciseCheck($hasher_stub, $cookieManager_mock, $credentials);
    }

    /**
     * @return array
     */
    private function prepareHasherCredentialsAndUserRepo($remember, $passwordIsGood = true)
    {
        list($credentials, $hasher_stub) = $this->prepareHasherAndCredentials($remember, $passwordIsGood);

        $user = new User();
        $user->initialize([
            'id'       => $this->userId,
            'username' => new Username(self::USERNAME),
            'password' => new Password(self::PASS, $hasher_stub->reveal()),
            'email'    => new Email('hola@azofaifa.com')
        ]);

        $this->userRepository_double->getByUsername(self::USERNAME)->willReturn($user);
        return array($credentials, $hasher_stub);
    }

    /**
     * @param $hasher_stub
     * @param $cookieManager_double
     * @param $credentials
     * @return mixed
     */
    private function exerciseCheck($hasher_stub, $cookieManager_double, $credentials)
    {
        $sut = new AuthService($this->getDi()->get('entityManager'), $hasher_stub->reveal(), $cookieManager_double->reveal());
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
        $credentials = ['username' => self::USERNAME, 'password' => self::PASS, 'remember' => $remember];

        $hasher_stub = $this->prophesize('EuroMillions\interfaces\IPasswordHasher');
        $hasher_stub->hashPassword(self::PASS)->willReturn(self::HASH);
        $hasher_stub->checkPassword(self::PASS, self::HASH)->willReturn($passwordIsGood);
        return array($credentials, $hasher_stub);
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

    private function expectFlushInEntityManager()
    {
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldBeCalled();
        $this->stubEntityManager($entityManager_stub);
    }
}