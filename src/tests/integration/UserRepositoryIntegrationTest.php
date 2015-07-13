<?php
namespace tests\integration;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\components\PhpassWrapper;
use EuroMillions\entities\User;
use EuroMillions\repositories\UserRepository;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\Username;
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
        $this->sut = $this->entityManager->getRepository('\EuroMillions\entities\User');
    }
    /**
     * method add
     * when calledWithValidUser
     * should storesCorrectlyInTheDatabase
     */
    public function test_add_calledWithValidUser_storesCorrectlyInTheDatabase()
    {
        $username = 'azofaifa';
        $password = 'passworD01';
        $email = 'hola@hola.com';
        $hasher = new NullPasswordHasher();
        list($user, $actual) = $this->exerciseAdd($username, $password, $hasher, $email);

        $this->assertEquals($user, $actual);
    }

    /**
     * method add
     * when calledWithARealPasswordHasherInTheUser
     * should storeUserWithTheCorrectHash
     */
    public function test_add_calledWithARealPasswordHasherInTheUser_storeUserWithTheCorrectHash()
    {
        $username = 'azofaifa';
        $password = 'passworD01';
        $email = 'hola@hola.com';
        $hasher = new PhpassWrapper();
        /** @var User $actual */
        list($user, $actual) = $this->exerciseAdd($username, $password, $hasher, $email);
        $this->assertTrue($hasher->checkPassword($password, $actual->getPassword()->password()));
    }

    /**
     * @param $username
     * @param $password
     * @param $hasher
     * @param $email
     * @return array
     */
    private function exerciseAdd($username, $password, $hasher, $email)
    {
        /** @var UserRepository $sut */
        $user = new User();
        $user->initialize([
            'id'       => $this->sut->nextIdentity(),
            'username' => new Username($username),
            'password' => new Password($password, $hasher),
            'email'    => new Email($email),
            'balance' => new Money(3000, new Currency('EUR')),
        ]);
        /** @var Password $hashed_pass */
        $hashed_pass = $user->getPassword();
        $this->sut->add($user);
        $this->entityManager->flush();
        $actual = $this->entityManager
            ->createQuery(
                'SELECT u'
                . ' FROM \EuroMillions\entities\User u'
                . ' WHERE u.username.username = :username AND u.password.password = :password AND u.email.email = :email')
            ->setMaxResults(1)
            ->setParameters(['username' => $username, 'password' => $hashed_pass->password(), 'email' => $email])
            ->getResult()[0];
        return array($user, $actual);
    }

    /**
     * method getByUsername
     * when called
     * should returnProperResult
     */
    public function test_getByUsername_called_returnProperResult()
    {
        $actual = $this->sut->getByUsername('algarrobo');
        $this->assertEquals('9098299B-14AC-4124-8DB0-19571EDABE55', $actual->getId());
    }
}