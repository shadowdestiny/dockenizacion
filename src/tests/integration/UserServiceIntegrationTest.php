<?php
namespace tests\integration;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\config\Namespaces;
use EuroMillions\entities\User;
use EuroMillions\entities\CreditCardPaymentMethod;
use EuroMillions\vo\CardHolderName;
use EuroMillions\vo\CardNumber;
use EuroMillions\vo\CreditCard;
use EuroMillions\vo\CVV;
use EuroMillions\vo\Email;
use EuroMillions\vo\ExpiryDate;
use EuroMillions\vo\Password;
use EuroMillions\vo\UserId;
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
        $actual = $sut->getBalance($userId);
        $this->assertEquals($expected, $actual);
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
        $sut = $this->getDomainServiceFactory()->getUserService(null, null, null, $paymentProvider_double->reveal());
        $sut->recharge($user,$paymentMethod,$amount);
        $this->entityManager->detach($user);
        $user = $userRepository->getByEmail($email);
        $actual = $user->getBalance()->getAmount();
        $this->assertEquals($expected,$actual);
    }

    public function getUserIdsAndExpectedBalances()
    {
        return [
            ['9098299B-14AC-4124-8DB0-19571EDABE55', '€3,000.05'],
        ];
    }

    /**
     * method addNewPaymentMethod
     * when called
     * should increasePaymentMethodByUser
     */
    public function test_addNewPaymentMethod_called_increasePaymentMethodByUser()
    {
        $expected =1;
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
        $sut = $this->getDomainServiceFactory()->getUserService(null, null, null, $paymentProvider_double->reveal());
        $sut->addNewPaymentMethod($paymentMethod);
        $this->entityManager->detach($paymentMethod);
        $paymentMethodCollection = $sut->getPaymentMethods($user->getId());
        $actual = count($paymentMethodCollection->getValues());
        $this->assertEquals($expected,$actual);
    }



}