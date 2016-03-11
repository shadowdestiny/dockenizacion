<?php
namespace EuroMillions\tests\integration;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;

class UserServiceIntegrationTest extends DatabaseIntegrationTestBase
{
    protected $currencyConversionService_double;
    protected $paymentProvider_double;
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'languages',
            'play_configs',
            'notifications',
            'users_notifications'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
        $this->paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
    }

    public function getUserIdsAndExpectedBalances()
    {
        return [
            ['9098299B-14AC-4124-8DB0-19571EDABE55', '€3,000.05'],
        ];
    }


    public function getUserIdsAndExpectedBalancesAndCurrency()
    {
        return [
            ['9098299B-14AC-4124-8DB0-19571EDABE59', '€30.00'],
        ];
    }


    /**
     * method getMyActivePlays
     * when called
     * should returnArrayWithPlayConfigs
     */
    public function test_getMyActivePlays_called_returnArrayWithPlayConfigs()
    {
        //EMTD hacer este test primero para mañana 11/03/2016
        $this->markTestIncomplete('Redo the test: look at the next one as an example');
        $user = $this->getUser();
        $expected = 1;
        $sut = $this->getSut();
        $actual = count($sut->getMyActivePlays($user->getId())->getValues());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getMyInactivePlays
     * when called
     * should returnArrayWithInactivePlayConfigs
     */
    public function test_getMyInactivePlays_called_returnArrayWithInactivePlayConfigs()
    {
        $user = $this->getUser();
        $expected_play_config_id = 4;
        $sut = $this->getSut();
        $result = $sut->getMyInactivePlays($user->getId());
        $play_configs = $result->getValues();
        $this->assertTrue($result->success(), 'The result should have success');
        $this->assertInternalType('array', $play_configs, 'The values returned should be an array');
        $this->assertCount(1, $play_configs, 'The array should contain a single element');
        $this->assertEquals($expected_play_config_id, $play_configs[0]->getId(), 'The id of the inactive play config should be: '.$expected_play_config_id);
    }

    /**
     * method getActiveNotificationsByUser
     * when called
     * should returnCollectionWithUserNotifications
     */
    public function test_getActiveNotificationsByUser_called_returnCollectionWithUserNotifications()
    {
        $expected = 2;
        $email = 'algarrobo@currojimenez.com';
        $userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');
        /** @var User $user */
        $user = $userRepository->getByEmail($email);
        $sut = $this->getSut();
        $actual = count($sut->getActiveNotificationsByUser($user)->getValues());
        $this->assertEquals($expected,$actual);
    }

    public function getUser()
    {
        $userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');

        $email = 'algarrobo@currojimenez.com';
        return $userRepository->getByEmail($email);
    }

    /**
     * @return \EuroMillions\web\services\UserService
     */
    protected function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getUserService(
            $this->currencyConversionService_double->reveal(),
            null,
            $this->paymentProvider_double->reveal()
        );
        return $sut;
    }


}