<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\emerchant\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\emerchant\eMerchantConfig;
use EuroMillions\web\vo\dto\PaymentProviderDTO;
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
        $params = $this->createArrayData($data->toArray());
        /** @var Response $result */
        $result = $this->gatewayClient->send($params);
        $header = $result->header;
        $body = json_decode($result->body);
        if( $header->statusCode != 200 ) {
            return new PaymentProviderResult(false,$header->statusMessage,$header->statusMessage);
        }
        return new PaymentProviderResult($body->status === "ok", $header->statusMessage);
    }

    private function createArrayData(array $data) {
        return [
            'idTransaction' => $data['idTransaction'],
            'userId' => $data['userId'],
            'amount' => $data['amount'],
            'creditCardNumber' => $data['creditCardNumber'],
            'cvc' => $data['cvv'],
            'expirationYear' => substr($data['expirationYear'], 2),
            'expirationMonth' => $data['expirationMonth'],
            'cardHolderName' => $data['cardHolderName'],
            'email' => $data['userEmail'],
            'ip' => $data['userIp'],
        ];
    }
}