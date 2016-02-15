<?php
namespace tests\unit;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\helpers\mothers\CreditCardChargeMother;
use tests\helpers\mothers\CreditCardMother;
use tests\base\UnitTestBase;
use tests\helpers\mothers\UserMother;

class WalletServiceUnitTest extends UnitTestBase
{
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
        $sut = $this->getDomainServiceFactory()->getWalletService($this->getEntityManagerDouble()->reveal());
        $actual = $sut->rechargeWithCreditCard($card_payment_provider->reveal(), $credit_card, $user, $credit_card_charge);
        $this->assertInstanceOf('EuroMillions\shared\interfaces\IResult', $actual);
        $this->assertEquals($payment_provider_result, $actual->success());
        $this->assertEquals($expected_wallet, $user->getWallet());
    }

}