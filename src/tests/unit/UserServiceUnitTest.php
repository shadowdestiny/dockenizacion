<?php
namespace tests\unit;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\components\NullPasswordHasher;
use EuroMillions\config\Namespaces;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\entities\CreditCardPaymentMethod;
use EuroMillions\vo\CardHolderName;
use EuroMillions\vo\CardNumber;
use EuroMillions\vo\ContactFormInfo;
use EuroMillions\vo\CreditCard;
use EuroMillions\vo\CVV;
use EuroMillions\vo\Email;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\EuroMillionsLuckyNumber;
use EuroMillions\vo\EuroMillionsRegularNumber;
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
    private $playRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'PaymentMethod' => $this->paymentMethodRepository_double,
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playRepository_double,

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
        $this->playRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        parent::setUp();
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
        $user = $this->getUser();
        $this->userRepository_double->find(Argument::any())->willReturn($user);
        $this->currencyService_double->toString($user->getBalance(), 'es_ES')->willReturn(new MoneyFormatter());
        $sut = $this->getSut();
        $actual = $sut->getBalance($user->getId(), 'es_ES');
        $this->assertInstanceOf('antonienko\MoneyFormatter\MoneyFormatter',$actual);
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
        $expected = new ServiceActionResult(true,'Your payment method was added');
        $this->exerciseAddNewPaymentMethod($expected);
    }

    /**
     * method addNewPaymentMethod
     * when NoPersist
     * should returnServiceActionResultFalse
     */
    public function test_addNewPaymentMethod_NoPersist_returnServiceActionResultFalse()
    {
        $expected = (new ServiceActionResult(false,'An exception ocurred while payment method was saved'));
        $this->exerciseAddNewPaymentMethodThrowException($expected);
    }

    /**
     * method addNewPaymentMethod
     * when called
     * should increasePaymentMethodByUser
     */
    public function test_addNewPaymentMethod_called_increasePaymentMethodByUser()
    {
        $expected =1;
        $creditCard = $this->getCreditCard();
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $user = $this->getUser();
        $user->setId(new UserId('bbf01cc4-5548-11e5-b753-0242ac110002'));
        $paymentMethod->setUser($user);
        $this->paymentMethodRepository_double->add(Argument::any())->willReturn(true);
        $this->userRepository_double->find(Argument::any())->willReturn($user);
        $this->paymentMethodRepository_double->getPaymentMethodsByUser(Argument::any())->willReturn([$paymentMethod]);
        $entityManager_stub = $this->getEntityManagerDouble();
        $this->stubEntityManager($entityManager_stub);
        $sut = $this->getSut();
        $result_add = $sut->addNewPaymentMethod($paymentMethod);
        $actual = count($result_add->getValues());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getPaymentMethods
     * when called
     * should returnServiceActionResultTrueWithArrayOfPaymentMethods
     */
    public function test_getPaymentMethods_called_returnServiceActionResultTrueWithArrayOfPaymentMethods()
    {
        $expected = new ServiceActionResult(true,array('EuroMillions\Entities\PaymentMethod'));
        $this->exerciseGetPaymentMethod($expected,['EuroMillions\Entities\PaymentMethod']);
    }

    /**
     * method getPaymentMethods
     * when calledWithValidUser
     * should returnServiceActionResultFalseWithEmptyArray
     */
    public function test_getPaymentMethods_calledWithValidUser_returnServiceActionResultFalseWithEmptyArray()
    {
        $expected = new ServiceActionResult(false,'You don\'t have any payment method registered');
        $this->exerciseGetPaymentMethod($expected);
    }


    /**
     * method getPaymentMethods
     * when calledWithInvalidUser
     * should returnServiceActionResultFalseWithEmptyArray
     */
    public function test_getPaymentMethods_calledWithInvalidUser_returnServiceActionResultFalseWithEmptyArray()
    {
        $expected = new ServiceActionResult(false,'You don\'t have any payment method registered');
        $this->exerciseGetPaymentMethod($expected,[],'43872489302fdkosfds');
    }

    /**
     * method getPaymentMethod
     * when calledWithInvalidIdPayment
     * should returnServiceActionResultFalse
     */
    public function test_getPaymentMethod_calledWithInvalidIdPayment_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false);
        $this->paymentMethodRepository_double->findOneBy(['id' => '1234'])->willThrow(new \Exception());
        $sut = $this->getSut();
        $actual = $sut->getPaymentMethod('1234');
        $this->assertEquals($expected,$actual);
    }


    /**
     * method getMyPlays
     * when called
     * should returnServiceActionResultTrueWithProperData
     */
    public function test_getMyPlays_called_returnServiceActionResultTrueWithProperData()
    {

        $playConfig = $this->getPlayConfig();
        $expected = new ServiceActionResult(true,$playConfig);
        $sut = $this->getSut();
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');
        $this->playRepository_double->getPlayConfigsActivesByUser($userId)->willReturn($playConfig);
        $actual = $sut->getMyPlaysActives($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getMyPlays
     * when calledWithInvalidUser
     * should throwException
     */
    public function test_getMyPlays_calledWithInvalidUser_throwException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $this->userRepository_double->find(Argument::any())->willReturn(null);
        $sut = $this->getSut();
        $sut->getPaymentMethods($this->getUser()->getId());
    }



    /**
     * method getMyPlays
     * when calledWithUserIDValid
     * should returnServiceActionResultFalseWithEmtpyValue
     */
    public function test_getMyPlays_calledWithUserIDValid_returnServiceActionResultFalseWithEmtpyValue()
    {
        $expected = new ServiceActionResult(false,'You don\'t have games');
        $sut = $this->getSut();
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');
        $this->playRepository_double->getPlayConfigsActivesByUser($userId)->willReturn(null);
        $actual = $sut->getMyPlaysActives($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updateUserData
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_updateUserData_called_returnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true,'Your data was update');
        $credentials = [
            'name'             => 'Raul',
            'surname'          => 'Mesa',
            'email'            => 'rmrbest@gmail.com',
            'password'         => 'passWord01',
            'confirm_password' => 'passWord01',
            'country'          => 'Spain',
            'street'           => 'Tres',
            'city'             => 'Barcelona',
            'zip'              => '08830',
            'phone_number'     => '626966592'

        ];
        $user = $this->getUser();
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn($user);
        $this->userRepository_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->updateUserData($credentials);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updateUserDate
     * when called
     * should throrExceptionAndReturnServiceActionResultFalse
     */
    public function test_updateUserDate_called_throrExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false,'Sorry, try it later');
        $credentials = [
            'name'             => 'Raul',
            'surname'          => 'Mesa',
            'email'            => 'rmrbest@gmail.com',
            'password'         => 'passWord01',
            'confirm_password' => 'passWord01',
            'country'          => 'Spain',
            'street'           => 'Tres',
            'city'             => 'Barcelona',
            'zip'              => '08830',
            'phone_number'     => '626966592'
        ];

        $user = $this->getUser();
        $this->userRepository_double->getByEmail($credentials['email'])->willReturn($user);
        $this->userRepository_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->willThrow('Exception');
        $sut = $this->getSut();
        $actual = $sut->updateUserData($credentials);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getAllUsersWithJackpotReminder
     * when called
     * should returnServiceActionResultTrueWithProperData
     */
    public function test_getAllUsersWithJackpotReminder_called_returnServiceActionResultTrueWithProperData()
    {
        $user = [$this->getUser()];
        $expected = new ServiceActionResult(true,$user);
        $this->userRepository_double->getUsersWithJackpotReminder()->willReturn($user);
        $sut = $this->getSut();
        $actual = $sut->getAllUsersWithJackpotReminder();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getAllUsersWithJackpotReminder
     * when called
     * should returnServiceActionResultFalse
     */
    public function test_getAllUsersWithJackpotReminder_called_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false);
        $this->userRepository_double->getUsersWithJackpotReminder()->willReturn(null);
        $sut = $this->getSut();
        $actual = $sut->getAllUsersWithJackpotReminder();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method editMyPaymentMethod
     * when called
     * should updateDataAndReturnServiceActionResultTrue
     */
    public function test_editMyPaymentMethod_called_updateDataAndReturnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true,'Your credit card data was updating');
        $id = 1;
        $creditCard = new CreditCard(new CardHolderName('Test01 test02 test03'),
            new CardNumber('5500005555555559'),
            new ExpiryDate('01/20'),
            new CVV('456')
        );

        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $paymentMethod->setId($id);
        $paymentMethod->setUser($this->getUser());
        $paymentMethod->setType(1);
        $paymentMethod->setPaymentMethodType(1);

        $edit_credit_card = [
            'cardHolderName' => 'Test01 test02 test03',
            'cardNumber' => '5500005555555559',
            'month' => '01',
            'year'  => '20',
            'cvv'   => '456'
        ];
        $this->paymentMethodRepository_double->findOneBy(['id' => 1])->willReturn($paymentMethod);
        $this->paymentMethodRepository_double->add($paymentMethod)->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        //$entityManager_stub->detach();
        $entityManager_stub->flush($paymentMethod)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->editMyPaymentMethod($id,$edit_credit_card);
        $this->assertEquals($expected,$actual);
    }


    private function getPlayConfig()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));

        $playConfig = new PlayConfig();
        $playConfig->setUser($this->getUser());
        $playConfig->setLine($euroMillionsLine);
        $playConfig->setActive(true);
        $playConfig->setDrawDays(2);

        return $playConfig;
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
        $sut = $this->getDomainServiceFactory()->getUserService($this->currencyService_double->reveal(),
                                                                $this->emailService_double->reveal(),
                                                                $this->paymentProviderService_double->reveal());
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
        $paymentMethod->setUser($user);
        //$this->paymentMethodRepository_double->add(Argument::any())->willThrow(new \Exception('An exception ocurred while payment method was saved'));
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($paymentMethod)->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->addNewPaymentMethod($paymentMethod);
        $this->assertEquals($expected, $actual);
    }

    protected function exerciseAddNewPaymentMethodThrowException(ServiceActionResult $expected)
    {
        $user = $this->getUser();
        $creditCard = $this->getCreditCard();
        $paymentMethod = new CreditCardPaymentMethod($creditCard);
        $paymentMethod->setUser($user);
        $this->paymentMethodRepository_double->add(Argument::any())->willThrow(new \Exception('An exception ocurred while payment method was saved'));
        $sut = $this->getSut();
        $actual = $sut->addNewPaymentMethod($paymentMethod);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $expected
     * @param array $return
     * @return ServiceActionResult
     */
    protected function exerciseGetPaymentMethod($expected,$return = [], $userId = '9098299B-14AC-4124-8DB0-19571EDABE55')
    {
        $userId = new UserId($userId);
        $user = $this->getUser();
        $this->userRepository_double->find($userId)->willReturn($user);
        $this->paymentMethodRepository_double->getPaymentMethodsByUser($user)->willReturn($return);
        $actual = $this->getSut()->getPaymentMethods($userId);
        $this->assertEquals($expected,$actual);
    }

    protected function getRegularNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    protected function getLuckyNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }

}