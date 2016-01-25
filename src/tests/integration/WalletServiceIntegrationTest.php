<?php


namespace tests\integration;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\entities\User;
use Money\Currency;
use Money\Money;
use tests\base\DatabaseIntegrationTestBase;
use tests\helpers\mothers\CreditCardMother;

class WalletServiceIntegrationTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
        ];
    }

    /**
     * method rechargeWithCreditCard
     * when chargeIsAllowed
     * should persistProperAmountInUserWallet
     */
    public function test_rechargeWithCreditCard_chargeIsAllowed_persistProperAmountInUserWallet()
    {
        $amount = new Money(2000, new Currency('EUR'));
        $expected_wallet = Wallet::create(5000, 0);
        $user_repository = $this->entityManager->getRepository('EuroMillions\web\entities\User');
        /** @var User $user */
        $user = $user_repository->find('9098299B-14AC-4124-8DB0-19571EDABE59');
        $credit_card = CreditCardMother::aValidCreditCard();
        $payment_provider_stub = $this->getInterfaceWebDouble('ICardPaymentProvider');
        $payment_provider_stub->charge($amount, $credit_card)->willReturn(new PaymentProviderResult(true));

        $sut = $this->getDomainServiceFactory()->getWalletService($this->entityManager);

        $sut->rechargeWithCreditCard($payment_provider_stub->reveal(), $credit_card, $user, $amount);

        $this->entityManager->detach($user);

        $actual_user = $user_repository->find('9098299B-14AC-4124-8DB0-19571EDABE59');
        $this->assertEquals($expected_wallet, $actual_user->getWallet());
    }
}