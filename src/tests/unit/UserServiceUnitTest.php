<?php
namespace tests\unit;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\config\Namespaces;
use EuroMillions\entities\User;
use EuroMillions\entities\CreditCardPaymentMethod;
use EuroMillions\vo\CardHolderName;
use EuroMillions\vo\CardNumber;
use EuroMillions\vo\ContactFormInfo;
use EuroMillions\vo\CreditCard;
use EuroMillions\vo\CVV;
use EuroMillions\vo\Email;
use EuroMillions\vo\ExpiryDate;
use EuroMillions\vo\Password;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;


class UserServiceUnitTest extends UnitTestBase
{
    private $userRepository_double;
    private $currencyService_double;
    private $storageStrategy_double;
    private $emailService_double;
    private $paymentProviderService_double;
    private $paymentMethodRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'PaymentMethod' => $this->paymentMethodRepository_double
        ];
    }

    public function setUp()
    {
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        $this->storageStrategy_double = $this->getInterfaceDouble('IUsersPreferencesStorageStrategy');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->paymentProviderService_double = $this->getServiceDouble('PaymentProviderService');
        $this->paymentMethodRepository_double = $this->getRepositoryDouble('PaymentMethodRepository');
        parent::setUp();
    }

    /**
     * method getMyCurrencyNameAndSymbol
     * when currencyIsSetInStorage
     * should returnProperValueAndSymbol
     * @dataProvider getCurrenciesAndSymbols
     */
    public function test_getMyCurrencyNameAndSymbol_currencyIsSetInStorage_returnProperValueAndSymbol($code, $name, $symbol)
    {
        $this->storageStrategy_double->getCurrency()->willReturn(new Currency($code));
        $sut = $this->getSut();
        $actual = $sut->getMyCurrencyNameAndSymbol();
        $this->assertEquals(['symbol' => $symbol, 'name' => $name], $actual);
    }

    public function getCurrenciesAndSymbols()
    {
        return [
            ['EUR', 'Euro', '€'],
            ['USD', 'US Dollar', '$'],
            ['COP', 'Colombian Peso', 'COP'],
            ['OMR', 'Omani Rial', 'OMR'],
        ];
    }

    /**
     * method getMyCurrencyNameAndSymbol
     * when currencyIsNotSet
     * should returnEuro
     */
    public function test_getMyCurrencyNameAndSymbol_currencyIsNotSet_returnEuro()
    {
        $this->storageStrategy_double->getCurrency(Argument::any())->willReturn(null);
        $sut = $this->getSut();
        $actual = $sut->getMyCurrencyNameAndSymbol();
        $this->assertEquals(['symbol' => '€', 'name' => 'Euro'], $actual);
    }

    /**
     * method getCurrency
     * when calledWithoutCurrencyOnStorage
     * should returnEuro
     */
    public function test_getCurrency_calledWithoutCurrencyOnStorage_returnEuro()
    {
        $this->storageStrategy_double->getCurrency(Argument::any())->willReturn(null);
        $expected = new Currency('EUR');
        $sut = $this->getSut();
        $actual = $sut->getCurrency();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method contactRequest
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_contactRequest_called_returnServiceActionResultTrue()
    {
        $sut = $this->getSut();
        $actual = $sut->contactRequest(
            new ContactFormInfo(new Email('raul.mesa@panamedia.net'),
            'Raul Mesa Ros',
            'I have a problem',
            'Playing the game'
            )
        );
        $this->assertInstanceOf('Euromillions\vo\ServiceActionResult', $actual);
        $this->assertTrue($actual->success());
    }

    /**
     * method getBalance
     * when called
     * should returnBalanceByUser
     */
    public function test_getBalance_called_returnBalanceByUser()
    {
        $currency = 'EUR';
        $user = $this->getUser();
        $this->userRepository_double->find(Argument::any())->willReturn($user);
        $this->currencyService_double->toString(Argument::any())->willReturn('50');
        $sut = $this->getSut();
        $actual = $sut->getBalance($user->getId());
        $this->assertGreaterThan(0, $actual, "Amount should be an greater than 0");
    }

    /**
     * method recharge
     * when calledAndPaymentProviderSuccess
     * should returnServiceResultActionTrue
     */
    public function test_recharge_calledAndPaymentProviderSuccess_returnServiceResultActionTrue()
    {
        $creditCard = $this->getCreditCard();
        $user = $this->getUser();
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $amount = new Money(5000, new Currency('EUR'));
        $this->paymentProviderService_double->charge($paymentMethod,$amount)->willReturn(true);
        $this->userRepository_double->add($user);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $sut = $this->getSut();
        $actual = $sut->recharge($user,$paymentMethod, $amount);
        $this->assertTrue($actual->success());
    }

    /**
     * method recharge
     * when calledPassAmountZero
     * should returnServiceResultActionFalse
     */
    public function test_recharge_calledPassAmountZero_returnServiceResultActionFalse()
    {
        $creditCard = $this->getCreditCard();
        $user = $this->getUser();
        $sut = $this->getSut();
        $actual = $sut->recharge($user,new CreditCardPaymentMethod($creditCard),
            new Money(0, new Currency('EUR')));
        $this->assertFalse($actual->success());
    }

//EMTEST probar que se suma el amount

    /**
     * method recharge
     * when callPassAmountGreaterThanZeroAndProviderReturnsOkResult
     * should increaseUserBalanceInTheGivenAmount
     */
    public function test_recharge_callPassAmountGreaterThanZeroAndProviderReturnsOkResult_increaseUserBalanceInTheGivenAmount()
    {
        $expected = new ServiceActionResult(true, new Money(10000, new Currency('EUR')));
        $this->exerciseRecharge(true, $expected->success());
    }

    /**
     * method recharge
     * when ProviderReturnKoResult
     * should leaveUserBalanceLikeBeforeAndReturnServiceActionResultWithFalse
     */
    public function test_recharge_ProviderReturnKoResult_leaveUserBalanceLikeBeforeAndReturnServiceActionResultWithFalse()
    {
        $expected = new ServiceActionResult(false, 'Provider denied the operation');
        $this->exerciseRecharge(false, $expected->success());
    }

    /**
     * method addNewPaymentMethod
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_addNewPaymentMethod_called_returnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true);
        $this->exerciseAddNewPaymentMethod($expected);
    }

    /**
     * method addNewPaymentMethod
     * when NoPersist
     * should returnServiceActionResultFalse
     */
    public function test_addNewPaymentMethod_NoPersist_returnServiceActionResultFalse()
    {
        $expected = (new ServiceActionResult(false,'Error inserting payment method'));
        $this->exerciseAddNewPaymentMethod($expected);
    }
    
    


    /**
     * @param string $currency
     * @return User
     */
    private function getUser($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'balance' => new Money(5000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    /**
     * @return CreditCard
     */
    private function getCreditCard()
    {
        return $creditCard = new CreditCard(new CardHolderName('Raul Mesa Ros'),
            new CardNumber('5500005555555559'),
            new ExpiryDate('10/19'),
            new CVV('123')
        );
    }


    /**
     * @return \EuroMillions\services\UserService
     */
    protected function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getUserService($this->currencyService_double->reveal(), $this->storageStrategy_double->reveal(), $this->emailService_double->reveal(), $this->paymentProviderService_double->reveal());
        return $sut;
    }

    /**
     * @param $payment_provider_result
     * @param $expected
     */
    protected function exerciseRecharge($payment_provider_result, $expected)
    {
        $creditCard = $this->getCreditCard();
        $user = $this->getUser();
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $amount = new Money(5000, new Currency('EUR'));
        $this->paymentProviderService_double->charge($paymentMethod, $amount)->willReturn($payment_provider_result);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->recharge($user, $paymentMethod, $amount);
        $this->assertEquals($expected, $actual->success());
    }

    /**
     * @param $expected
     */
    protected function exerciseAddNewPaymentMethod(ServiceActionResult $expected)
    {
        $user = $this->getUser();
        $creditCard = $this->getCreditCard();
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $this->paymentMethodRepository_double->add(Argument::any())->willReturn($expected->success());
        $sut = $this->getSut();
        $actual = $sut->addNewPaymentMethod($paymentMethod);
        $this->assertEquals($expected, $actual);
    }

}