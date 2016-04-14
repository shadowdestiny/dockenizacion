<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\web\services\WalletService;
use EuroMillions\web\vo\dto\WalletDTO;
use EuroMillions\web\vo\enum\TransactionType;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\helpers\mothers\CreditCardChargeMother;
use EuroMillions\tests\helpers\mothers\CreditCardMother;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;

class WalletServiceUnitTest extends UnitTestBase
{


    private $currencyConversionService_double;
    private $transactionService_double;


    public function setUp()
    {
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
        $this->transactionService_double = $this->getServiceDouble('TransactionService');
        parent::setUp();
    }

    /**
     * method rechargeWithCreditCard
     * when providerRejectsCharge
     * should returnResultFalseAndLeaveWalletIntact
     */
    public function test_rechargeWithCreditCard_providerRejectsCharge_returnResultFalseAndLeaveWalletIntact()
    {
        $amount = new Money(2000, new Currency('EUR'));
        $this->exerciseRechargeWithCreditCard(50000, false, $amount);
    }

    /**
     * method rechargeWithCreditCard
     * when providerAcceptsCharge
     * should returnResultTrueAndIncreaseUploadedAmountInWallet
     */
    public function test_rechargeWithCreditCard_providerAcceptsCharge_returnResultTrueAndIncreaseUploadedAmountInWallet()
    {
        $amount = new Money(2000, new Currency('EUR'));
        $this->exerciseRechargeWithCreditCard(52000, true, $amount);
    }

    /**
     * method rechargeWithCreditCard
     * when providerAcceptsCharge
     * should returnResultTrueAndIncreaseUploadedAmountWithoutFeeCharge
     */
    public function test_rechargeWithCreditCard_providerAcceptsCharge_returnResultTrueAndIncreaseUploadedAmountWithoutFeeCharge()
    {
        $amount = new Money(12000, new Currency('EUR'));
        $this->exerciseRechargeWithCreditCard(62000, true, $amount);
    }

    /**
     * method rechargeWithCreditCard
     * when providerAcceptsCharge
     * should returnResultTrueAndIncreaseUploadedAmountWithFeeCharge
     */
    public function test_rechargeWithCreditCard_providerAcceptsCharge_returnResultTrueAndIncreaseUploadedAmountWithFeeCharge()
    {
        $amount = new Money(1000, new Currency('EUR'));
        $this->exerciseRechargeWithCreditCard(51000, true, $amount);
    }

    /**
     * method getWalletDTO
     * when called
     * should returnProperWalletDTO
     */
    public function test_getWalletDTO_called_returnProperWalletDTO()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $user->setWinningAbove(new Money(10000, new Currency('EUR')));
        $uploaded = new Money(1000, new Currency('EUR'));
        $uploaded_string = '€ 10.00';
        $expected = new WalletDTO($uploaded_string,$uploaded_string,$uploaded_string,$uploaded_string);
        $this->exerciseConvert($uploaded, $user, $uploaded_string);
        $sut = new WalletService($this->getEntityManagerRevealed(), $this->currencyConversionService_double->reveal(),$this->transactionService_double->reveal());
        $actual = $sut->getWalletDTO($user);
        $this->assertInstanceOf('EuroMillions\web\vo\dto\WalletDTO', $actual);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getWalletDTO
     * when called
     * should returnNull
     */
    public function test_getWalletDTO_called_returnNull()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $user->setWinningAbove(new Money(10000, new Currency('EUR')));
        $sut = new WalletService($this->getEntityManagerRevealed(), $this->currencyConversionService_double->reveal(),$this->transactionService_double->reveal());
        $actual = $sut->getWalletDTO($user);
        $this->assertNull($actual);
    }

    /**
     * method payWithWallet
     * when called
     * should 
     */
    public function test_rechargeWithWallet_called_()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $expected_wallet = Wallet::create(4750);
        $playConfig = PlayConfigMother::aPlayConfig()->build();
        $data = [
            'lottery_id' => $playConfig->getLottery()->getId(),
            'numBets' => $playConfig->getId()
        ];
        $date = new \DateTime();
        $sut = new WalletService($this->getEntityManagerRevealed(), $this->currencyConversionService_double->reveal(),$this->transactionService_double->reveal());
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldBeCalled();
        $this->transactionService_double->storeTransaction(TransactionType::AUTOMATIC_PURCHASE,$data,$user->getId(), $date)->shouldBeCalled();
        $sut->payWithWallet($user,$playConfig);
        $this->assertEquals($expected_wallet, $user->getWallet());
    }

    /**
     * @param $expected_wallet_amount
     * @param $payment_provider_result
     */
    private function exerciseRechargeWithCreditCard($expected_wallet_amount, $payment_provider_result, $amount)
    {
        $user = UserMother::aUserWith500Eur()->build();
        $expected_wallet = Wallet::create($expected_wallet_amount);
        $card_payment_provider = $this->getInterfaceWebDouble('ICardPaymentProvider');
        $card_payment_provider->charge(Argument::any(), Argument::any())->willReturn(new PaymentProviderResult($payment_provider_result));
        $credit_card = CreditCardMother::aValidCreditCard();
        $credit_card_charge = CreditCardChargeMother::aValidCreditCardChargeWithAmountInParam($amount);
        $sut = new WalletService($this->getEntityManagerRevealed(), $this->currencyConversionService_double->reveal(), $this->transactionService_double->reveal());
        $actual = $sut->rechargeWithCreditCard($card_payment_provider->reveal(), $credit_card, $user, $credit_card_charge);
        $this->assertInstanceOf('EuroMillions\shared\interfaces\IResult', $actual);
        $this->assertEquals($payment_provider_result, $actual->success());
        $this->assertEquals($expected_wallet, $user->getWallet());
    }

    /**
     * @param $uploaded
     * @param $user
     * @param $uploaded_string
     */
    private function exerciseConvert($uploaded, $user, $uploaded_string)
    {
        $this->currencyConversionService_double->convert(Argument::any(), Argument::any())->willReturn($uploaded);
        $this->currencyConversionService_double->toString($uploaded, $user->getLocale())->willReturn($uploaded_string);
        $this->currencyConversionService_double->convert(Argument::any(), Argument::any())->willReturn($uploaded);
        $this->currencyConversionService_double->toString($uploaded, $user->getLocale())->willReturn($uploaded_string);
        $this->currencyConversionService_double->convert(Argument::any(), Argument::any())->willReturn($uploaded);
        $this->currencyConversionService_double->toString($uploaded, $user->getLocale())->willReturn($uploaded_string);
        $this->currencyConversionService_double->convert(Argument::any(), Argument::any())->willReturn($uploaded);
        $this->currencyConversionService_double->toString($uploaded, $user->getLocale())->willReturn($uploaded_string);
    }

}