<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\emerchant\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use Phalcon\Http\Client\Response;

class eMerchantPaymentProvider implements ICardPaymentProvider
{

    private $gatewayClient;

    public function __construct(eMerchantConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
    }

    /**
     * @param PaymentProviderDTO $data
     * @return PaymentProviderResult
     */
    public function charge(PaymentProviderDTO $data)
    {
        $params = $data->toArray();
        /** @var Response $result */
        $result = $this->gatewayClient->send($params);
        $header = $result->header;
        $body = json_decode($result->body);
        if( $header->statusCode != 200 ) {
            return new PaymentProviderResult(false,$header->statusMessage,$header->statusMessage);
        }
        return new PaymentProviderResult($body->status === "ok", $header->statusMessage);
    }

    public function getName()
    {
        return PaymentProviderEnum::EMERCHANT;
    }

    /**
     * @return IPaymentResponseRedirect
     */
    public function getResponseRedirect()
    {
        // TODO: Implement getResponseRedirect() method.
    }
}