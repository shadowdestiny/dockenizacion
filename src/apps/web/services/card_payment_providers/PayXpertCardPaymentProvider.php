<?php
namespace EuroMillions\web\services\card_payment_providers;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

include 'payxpert/GatewayClient.php';

class PayXpertCardPaymentProvider implements ICardPaymentProvider
{
    private $gatewayClient;

    public function __construct(PayXpertConfig $config)
    {
        //EMTD inject this client
        $this->gatewayClient = new \GatewayClient(PayXpertConfig::URL, $config->getOriginatorId(), $config->getApiKey());
    }


    public function charge(Money $amount, CreditCard $card)
    {
        $transaction = $this->gatewayClient->newTransaction('CCSale');
        $transaction->setTransactionInformation('100', 'EUR', 'order title');
        $transaction->setCardInformation($card->getCardNumbers(), $card->getCVV(), $card->getHolderName(), $card->getExpiryMonth(), $card->getExpiryYear());
        $transaction->setShopperInformation('NA', 'NA', 'NA', 'NA', 'NA', 'ZZ', 'NA', 'NA');
        return $transaction->send();
    }
}