<?php
namespace tests\integration\fixtures;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\entities\User;
use EuroMillions\repositories\UserRepository;
use tests\base\DatabaseIntegrationTestBase;

class AuthServiceIntegrationTest extends DatabaseIntegrationTestBase
{
    /** @var UserRepository $user_repo */
    private $userRepository;

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->userRepository = $this->entityManager->getRepository(self::ENTITIES_NS.'User');
    }
    /**
     * method register
     * when calledWithProperCredentials
     * should storeUserOnDatabase
     */
    public function test_register_calledWithProperCredentials_storeUserOnDatabase()
    {
        $credentials = [
            'name'             => 'Antonio',
            'surname'          => 'Hernández',
            'email'            => 'antonio@panamedia.net',
            'password'         => 'passWord01',
            'confirm_password' => 'passWord01',
            'country'          => 'Spain',
        ];
        $sut = $this->getDomainServiceFactory()->getAuthService(new NullPasswordHasher());
        $sut->register($credentials);
        $actual = $this->userRepository->getByEmail('antonio@panamedia.net');
        $this->assertNotNull($actual);
    }

    /**
     * method validateEmailToken
     * when tokenValidates
     * should setUserAsValidated
     */
    public function test_validateEmailToken_tokenValidates_setUserAsValidated()
    {
        $email = 'algarrobo@currojimenez.com';
        /** @var User $user */
        $user = $this->userRepository->getByEmail($email);
        $this->assertFalse($user->getValidated(), "The user is validated yet");
        $sut = $this->getDomainServiceFactory()->getAuthService();
        $token = 'azoafaifo';
        $validation_token_generator = $this->prophesize('EuroMillions\interfaces\IEmailValidationToken');
        $validation_token_generator->validate($email, $token)->willReturn(true);
        $actual = $sut->validateEmailToken($user, $token, $validation_token_generator->reveal());
        $this->assertTrue($actual->success(), "The service reported failure");
        $this->entityManager->detach($user);
        $user = $this->userRepository->getByEmail($email);
        $this->assertTrue($user->getValidated(), "The user is not validated on the database");
    }
}