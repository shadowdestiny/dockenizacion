<?php
namespace tests\integration;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use tests\base\DatabaseIntegrationTestBase;

class UserServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'languages',
            'payment_methods',
            'play_configs',
            'notifications',
            'users_notifications'
        ];
    }

    /**
     * method getBalance
     * when called
     * should returnProperBalance
     * @dataProvider getUserIdsAndExpectedBalances
     * @param $uuid
     * @param $expected
     */
    public function test_getBalance_called_returnProperBalance($uuid, $expected)
    {
        $userId = new UserId($uuid);
        $dsf = $this->getDomainServiceFactory();
        $sut = $dsf->getUserService();
        $actual = $sut->getBalance($userId, 'en_US');
        $this->assertEquals($expected, $actual);
    }

    public function getUserIdsAndExpectedBalances()
    {
        return [
            ['9098299B-14AC-4124-8DB0-19571EDABE55', '€3,000.05'],
        ];
    }

    /**
     * method getBalanceWithUserCurrencyConvert
     * when called
     * should returnProperBalanceWithCurrencyConverted
     * @dataProvider getUserIdsAndExpectedBalancesAndCurrency
     * @param $uuid
     * @param $expected
     */
    public function test_getBalanceWithUserCurrencyConvert_called_returnProperBalanceWithCurrencyConverted($uuid, $expected)
    {
        $userId = new UserId($uuid);
        $dsf = $this->getDomainServiceFactory();
        $sut = $dsf->getUserService();
        $actual = $sut->getBalanceWithUserCurrencyConvert($userId,new Currency('EUR'));
        $this->assertEquals($expected,$actual);

    }

    public function getUserIdsAndExpectedBalancesAndCurrency()
    {
        return [
            ['9098299B-14AC-4124-8DB0-19571EDABE59', '€30.00'],
        ];
    }


    /**
     * method recharge
     * when called
     * should increaseUserBalanaceAndReturnNewBalanceValue
     * @dataProvider
     */
    public function test_recharge_called_increaseUserBalanaceAndReturnNewBalanceValue()
    {
        $expected = 306005;
        $userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');
        $email = 'algarrobo@currojimenez.com';
        /** @var User $user */
        $user = $userRepository->getByEmail($email);
        $paymentMethod = $this->getInterfaceWebDouble('ICardPaymentProvider')->reveal();
        $amount = new Money(6000, new Currency('EUR'));
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $paymentProvider_double->charge($paymentMethod,$amount)->willReturn(true);
        $sut = $this->getSut($paymentProvider_double);
        $sut->recharge($user,$paymentMethod,$amount);
        $this->entityManager->detach($user);
        $user = $userRepository->getByEmail($email);
        $actual = $user->getBalance()->getAmount();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getMyActivePlays
     * when called
     * should returnArrayWithPlayConfigs
     */
    public function test_getMyActivePlays_called_returnArrayWithPlayConfigs()
    {
        $this->markTestIncomplete('Redo the test: look at the next one as an example');
        $user = $this->getUser();
        $expected = 1;
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $sut = $this->getSut($paymentProvider_double);
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
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $sut = $this->getSut($paymentProvider_double);
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
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $sut = $this->getSut($paymentProvider_double);
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
     * @param $paymentProvider_double
     * @return \EuroMillions\web\services\UserService
     */
    protected function getSut($paymentProvider_double)
    {
        $sut = $this->getDomainServiceFactory()->getUserService(null, null, $paymentProvider_double->reveal());
        return $sut;
    }


}