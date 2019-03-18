<?php
namespace EuroMillions\web\services\card_payment_providers;

use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\payxpert\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\payxpert\PayXpertConfig;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use Money\Currency;

class PayXpertCardPaymentProvider implements ICardPaymentProvider
{
    private $gatewayClient;


    public function __construct(PayXpertConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config, $config->getOriginatorId(), $config->getApiKey());
    }


    public function charge(PaymentProviderDTO $data)
    {
        $arrayData = $data->toArray();

        $amount = $arrayData['amount'];
        $currency = $arrayData['currency'];
        $cardNumbers = $arrayData['creditCardNumber'];
        $cardCvv = $arrayData['cvv'];
        $cardHolderName= $arrayData['cardHolderName'];
        $cardExpiryMonth = $arrayData['expirationMonth'];
        $cardExpiryYear = $arrayData['expirationYear'];

        if( $currency != new Currency('EUR') ) {
            return new PaymentProviderResult(false, (object) ['errorCode' => '916', 'errorMessage' => 'No terminal defined for current currency and card type']);
        }
        $transaction = $this->gatewayClient->newTransaction('CCSale');
        $transaction->setTransactionInformation($amount, $currency, 'EuroMillions Wallet Recharge');
        $transaction->setCardInformation($cardNumbers, $cardCvv, $cardHolderName, $cardExpiryMonth, $cardExpiryYear);
        $transaction->setShopperInformation('NA', 'NA', 'NA', 'NA', 'NA', 'ZZ', 'NA', 'NA');
        $result = $transaction->send();
        return new PaymentProviderResult($result->errorCode === "000", $result);
    }

    public function getName()
    {
        return PaymentProviderEnum::PAYXPERT;
    }
}