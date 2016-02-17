<?php
namespace EuroMillions\web\services\card_payment_providers;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\payxpert\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\vo\CreditCard;
use Money\Currency;
use Money\Money;

class PayXpertCardPaymentProvider implements ICardPaymentProvider
{
    private $gatewayClient;

    public function __construct(PayXpertConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config, $config->getOriginatorId(), $config->getApiKey());
    }


    public function charge(Money $amount, CreditCard $card)
    {
        if( $amount->getCurrency() != new Currency('EUR') ) {
            return new PaymentProviderResult(false, (object) ['errorCode' => '916', 'errorMessage' => 'No terminal defined for current currency and card type']);
        }
        $transaction = $this->gatewayClient->newTransaction('CCSale');
        $transaction->setTransactionInformation($amount->getAmount(), $amount->getCurrency()->getName(), 'EuroMillions Wallet Recharge');
        $transaction->setCardInformation($card->getCardNumbers(), $card->getCVV(), $card->getHolderName(), $card->getExpiryMonth(), $card->getExpiryYear());
        $transaction->setShopperInformation('NA', 'NA', 'NA', 'NA', 'NA', 'ZZ', 'NA', 'NA');
        $result = $transaction->send();
        return new PaymentProviderResult($result->errorCode === "000", $result);
    }
}