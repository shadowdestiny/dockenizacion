<?php
namespace EuroMillions\tests\integration;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\helpers\builders\UserBuilder;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\Md5EmailValidationToken;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\components\UserId;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use Ramsey\Uuid\Uuid;

class UserRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{
    /** @var UserRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'users'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->sut = $this->entityManager->getRepository($this->getEntitiesToArgument('User'));
    }

    /**
     * method add
     * when calledWithValidUser
     * should storesCorrectlyInTheDatabase
     */
    public function test_add_calledWithValidUser_storesCorrectlyInTheDatabase()
    {
        $password = 'passworD01';
        $email = 'hola@hola.com';
        $hasher = new NullPasswordHasher();
        list($user, $actual) = $this->exerciseAdd($password, $hasher, $email);
        $this->assertEquals($user, $actual);
    }

    /**
     * method add
     * when calledWithARealPasswordHasherInTheUser
     * should storeUserWithTheCorrectHash
     */
    public function test_add_calledWithARealPasswordHasherInTheUser_storeUserWithTheCorrectHash()
    {
        $password = 'passworD01';
        $email = 'hola@hola.com';
        $hasher = new PhpassWrapper();
        $user_and_actual = $this->exerciseAdd($password, $hasher, $email);
        $this->assertTrue($hasher->checkPassword($password, $user_and_actual[1]->getPassword()->toNative()));
    }

    /**
     * @param $password
     * @param $hasher
     * @param $email
     * @return array
     */
    private function exerciseAdd($password, $hasher, $email)
    {
        /** @var UserRepository $sut */
        $user = new User();
        $user->initialize([
            'name'               => 'nombre',
            'surname'            => 'apellido',
            'country'            => 'pais',
            'password'           => new Password($password, $hasher),
            'email'              => new Email($email),
            'wallet'             => new Wallet(new Money(3000, new Currency('EUR'))),
            'validated'          => 0,
            'jackpot_reminder'   => 0,
            'show_modal_winning' => 0
        ]);
        /** @var Password $hashed_pass */
        $hashed_pass = $user->getPassword();
        $this->sut->add($user);
        $this->entityManager->flush($user);
        $actual = $this->entityManager
            ->createQuery(
                'SELECT u'
                . ' FROM \EuroMillions\web\entities\User u'
                . ' WHERE u.password.value = :password AND u.email.value = :email')
            ->setMaxResults(1)
            ->setParameters(['password' => $hashed_pass->toNative(), 'email' => $email])
            ->getResult()[0];
        return array($user, $actual);
    }

    /**
     * method getByEmail
     * when called
     * should returnProperResult
     */
    public function test_getByEmail_called_returnProperResult()
    {
        $actual = $this->sut->getByEmail('algarrobo@currojimenez.com');
        $this->assertEquals('9098299B-14AC-4124-8DB0-19571EDABE55', $actual->getId());
    }

    /**
     * method getByEmail
     * when calledWithNonExistingEmail
     * should returnNull
     */
    public function test_getByEmail_calledWithNonExistingEmail_returnNull()
    {
        $actual = $this->sut->getByEmail('nonexisting@email.com');
        $this->assertNull($actual);
    }

    /**
     * method getByToken
     * when called
     * should returnProperResult
     */
    public function test_getByToken_called_returnProperResult()
    {
        $actual = $this->sut->getByToken('fdsdsfsdffsd54353GFD1');
        $this->assertEquals('9098299B-14AC-4124-8DB0-19571EDABE55', $actual->getId());
    }

    /**
     * method getByToken
     * when calledWithTokenInvalid
     * should returnNull
     */
    public function test_getByToken_calledWithTokenInvalid_returnNull()
    {
        $actual = $this->sut->getByToken('1111aaaaaAAAA');
        $this->assertNull($actual);
    }

    /**
     * method register
     * when calledWithProperCredentials
     * should addUserToDb
     */
    public function test_register_calledWithProperCredentials_addUserToDb()
    {
        $credentials = [
            'email'    => UserBuilder::DEFAULT_EMAIL,
            'name'     => UserBuilder::DEFAULT_NAME,
            'surname'  => UserBuilder::DEFAULT_SURNAME,
            'password' => UserBuilder::DEFAULT_PASSWORD,
            'country'  => UserBuilder::DEFAULT_COUNTRY,
        ];
        $user = $this->sut->register($credentials, new NullPasswordHasher(), new Md5EmailValidationToken());
        $this->entityManager->detach($user);
        $actual = $this->sut->getByEmail(UserBuilder::DEFAULT_EMAIL);
        $this->assertEquals(UserBuilder::DEFAULT_NAME, $actual->getName());
        $this->assertEquals(UserBuilder::DEFAULT_SURNAME, $actual->getSurname());
        $this->assertEquals(UserBuilder::DEFAULT_PASSWORD, $actual->getPassword());
        $this->assertEquals(UserBuilder::DEFAULT_COUNTRY, $actual->getCountry());
        $this->assertTrue(Uuid::isValid($actual->getId()));
    }

    /**
     * method registerFromCheckout
     * when calledWithProperCredentials
     * should addUserToDb
     */
    public function test_registerFromCheckout_calledWithProperCredentials_addUserToDb()
    {
        $userId = UserId::create();
        $credentials = [
            'email'    => UserBuilder::DEFAULT_EMAIL,
            'name'     => UserBuilder::DEFAULT_NAME,
            'surname'  => UserBuilder::DEFAULT_SURNAME,
            'password' => UserBuilder::DEFAULT_PASSWORD,
            'country'  => UserBuilder::DEFAULT_COUNTRY,
        ];
        $user = $this->sut->registerFromCheckout($credentials, $userId , new NullPasswordHasher(), new Md5EmailValidationToken());
        $this->entityManager->detach($user);
        $actual = $this->sut->getByEmail(UserBuilder::DEFAULT_EMAIL);
        $this->assertEquals(UserBuilder::DEFAULT_NAME, $actual->getName());
        $this->assertEquals(UserBuilder::DEFAULT_SURNAME, $actual->getSurname());
        $this->assertEquals(UserBuilder::DEFAULT_PASSWORD, $actual->getPassword());
        $this->assertEquals(UserBuilder::DEFAULT_COUNTRY, $actual->getCountry());
        $this->assertEquals($userId, $actual->getId());
        $this->assertTrue(Uuid::isValid($userId));
    }



}