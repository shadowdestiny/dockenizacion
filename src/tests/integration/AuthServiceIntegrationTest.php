<?php
namespace EuroMillions\tests\integration;

use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\services\AuthService;

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
        $this->userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');
    }
    /**
     * method register
     * when calledWithProperCredentials
     * should storeUserOnDatabase
     */
    public function test_register_calledWithProperCredentials_storeUserOnDatabase()
    {
        $this->markTestSkipped('Raul, arréglalo cuando esté listo lo del mail de validación');
        $credentials = [
            'name'             => 'Antonio',
            'surname'          => 'Hernández',
            'email'            => 'antonio@panamedia.net',
            'password'         => 'passWord01',
            'confirm_password' => 'passWord01',
            'country'          => 'Spain',
            'jackpot_reminder'  => 0,
        ];
        $sut = $this->getSut();
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
        $sut = $this->getSut();
        $token = 'fdsdsfsdffsd54353GFD1';
        $validation_token_generator = $this->getInterfaceWebDouble('IEmailValidationToken');
        $validation_token_generator->validate($email, $token)->willReturn(true);
        $actual = $sut->validateEmailToken($token, $validation_token_generator->reveal());
        $this->assertTrue($actual->success(), "The service reported failure");
        $this->entityManager->detach($user);
        $user = $this->userRepository->getByEmail($email);
        $this->assertTrue($user->getValidated(), "The user is not validated on the database");
    }

    /**
     * @return AuthService
     */
    private function getSut()
    {
        $sut = new AuthService(
            $this->entityManager,
            new NullPasswordHasher(),
            $this->getInterfaceWebDouble('IAuthStorageStrategy')->reveal(),
            $this->getSharedInterfaceDouble('IUrlManager')->reveal(),
            $this->getServiceDouble('LogService')->reveal(),
            $this->getServiceDouble('EmailService')->reveal(),
            $this->getServiceDouble('UserService')->reveal()
        );
        return $sut;
    }

}