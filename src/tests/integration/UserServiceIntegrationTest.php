<?php
namespace tests\integration;

use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\CreditCardPaymentMethod;
use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\ExpiryDate;
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
        $creditCard = new CreditCard(new CardHolderName('Raul Mesa Ros'),
            new CardNumber('5500005555555559'),
            new ExpiryDate('10/19'),
            new CVV('123')
        );
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
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
     * method addNewPaymentMethod
     * when called
     * should increasePaymentMethodByUser
     */
    public function test_addNewPaymentMethod_called_increasePaymentMethodByUser()
    {
        $expected = 1;
        $userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');
        $email = 'algarrobo@currojimenez.com';

        /** @var User $user */
        $user = $userRepository->getByEmail($email);
        $creditCard = new CreditCard(new CardHolderName('Raul Mesa Ros'),
            new CardNumber('5500005555555559'),
            new ExpiryDate('10/19'),
            new CVV('123')
        );

        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $paymentMethod->setUser($user);
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $sut = $this->getSut($paymentProvider_double);
        $sut->addNewPaymentMethod($paymentMethod);
        $this->entityManager->detach($paymentMethod);
        $paymentMethodCollection = $sut->getPaymentMethods($user->getId());
        $actual = count($paymentMethodCollection->getValues());
        $this->assertEquals($expected,$actual);
    }


    /**
     * method getMyPlays
     * when called
     * should returnArrayWithPlayConfigs
     */
    public function test_getMyPlaysActives_called_returnArrayWithPlayConfigs()
    {
        list($user,$playConfig) = $this->getPlayConfigExpected();
        $expected = 1;
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $sut = $this->getSut($paymentProvider_double);
        $actual = count($sut->getMyPlaysActives($user->getId())->getValues());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getMyPlaysInActives
     * when called
     * should returnArrayWithPlaysConfigsInactives
     */
    public function test_getMyPlaysInActives_called_returnArrayWithPlaysConfigsInactives()
    {
        list($user,$playConfig) = $this->getPlayConfigExpected();
        $expected = 1;
        $paymentProvider_double = $this->getServiceDouble('PaymentProviderService');
        $sut = $this->getSut($paymentProvider_double);
        $actual = count($sut->getMyPlaysInActives($user->getId())->getValues());
        $this->assertGreaterThanOrEqual($expected,$actual);
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

    public function getPlayConfigExpected()
    {

        $userRepository = $this->entityManager->getRepository(Namespaces::ENTITIES_NS.'User');

        $email = 'algarrobo@currojimenez.com';
        /** @var User $user */
        $user = $userRepository->getByEmail($email);
        $reg_numbers = [1, 2, 3, 4, 5];
        $luc_numbers = [5, 8];

        $regular_numbers = function($numbers){
            $result = [];
            foreach ($numbers as $number) {
                $result[] = new EuroMillionsRegularNumber($number);
            }
            return $result;

        };

        $lucky_numbers = function($numbers){
            $result = [];
            foreach ($numbers as $number) {
                $result[] = new EuroMillionsLuckyNumber($number);
            }
            return $result;
        };

        $euroMillionsLine = new EuroMillionsLine($regular_numbers($reg_numbers),
            $lucky_numbers($luc_numbers));

        $playConfig = new PlayConfig();
        $playConfig->setId(1);
        $playConfig->setUser($user);
        $playConfig->setLine($euroMillionsLine);
        $playConfig->setActive(true);
        $playConfig->setDrawDays(25);
        $playConfig->setStartDrawDate(new \DateTime('2015-09-16 00:00:00'));
        $playConfig->setLastDrawDate(new \DateTime('2015-09-30 00:00:00'));

        return [$user,$playConfig];
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