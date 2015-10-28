<?php
namespace tests\integration;

use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\components\PhpassWrapper;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use tests\base\DatabaseIntegrationTestBase;

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
        /** @var User $actual */
        list($user, $actual) = $this->exerciseAdd($password, $hasher, $email);
        $this->assertTrue($hasher->checkPassword($password, $actual->getPassword()->toNative()));
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
            'id'       => $this->sut->nextIdentity(),
            'name'     => 'nombre',
            'surname'  => 'apellido',
            'country'  => 'pais',
            'password' => new Password($password, $hasher),
            'email'    => new Email($email),
            'balance'  => new Money(3000, new Currency('EUR')),
            'validated' => 0,
            'jackpot_reminder' => 0,
        ]);
        /** @var Password $hashed_pass */
        $hashed_pass = $user->getPassword();
        $this->sut->add($user);
        $this->entityManager->flush();
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
        $actual = $this->sut->getByToken('fdsdsfsdffsd54353GFD');
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



}