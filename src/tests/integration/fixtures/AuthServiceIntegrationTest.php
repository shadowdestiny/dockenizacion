<?php
namespace tests\integration\fixtures;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\repositories\UserRepository;
use tests\base\DatabaseIntegrationTestBase;

class AuthServiceIntegrationTest extends DatabaseIntegrationTestBase
{
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
        /** @var UserRepository $user_repo */
        $user_repo = $this->entityManager->getRepository(self::ENTITIES_NS.'User');
        $actual = $user_repo->getByEmail('antonio@panamedia.net');
        $this->assertNotNull($actual);
    }
}