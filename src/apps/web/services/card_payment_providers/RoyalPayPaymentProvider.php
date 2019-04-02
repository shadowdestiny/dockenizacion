<?php


namespace EuroMillions\web\services\card_payment_providers;

use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\services\card_payment_providers\royalpay\dto\RoyalPayBodyResponse;
use EuroMillions\web\services\card_payment_providers\royalpay\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;
use Money\Money;
use Phalcon\Http\Client\Response;

class RoyalPayPaymentProvider implements ICardPaymentProvider, IHandlerPaymentGateway
{
    use CountriesCollection;

    private $gatewayClient;

    /**
     * @var RoyalPayConfig $config
     */
    private $config;
    /**
     * @var PaymentCountry $paymentCountry
     */
    protected $paymentCountry;
    /**
     * @var PaymentWeight $paymentWeight
     */
    protected $paymentWeight;

    public function __construct(RoyalPayConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
        $this->config = $config;
        $this->paymentCountry = new PaymentCountry(['RU']); //Only from Mother Russia
        $this->paymentWeight = new PaymentWeight(100);
    }

    /**
     * @param PaymentProviderDTO $data
     * @return PaymentProviderResult
     * @throws \Exception
     */
    public function charge(PaymentProviderDTO $data)
    {
        $params = $data->toArray();

        /** @var Response $result */
        $result = $this->gatewayClient->send($params, 'payment');
        $header = $result->header;
        $body = RoyalPayBodyResponse::create(json_decode($result->body), $header->statusMessage);
        if ($header->statusCode != 201) {
            return new PaymentProviderResult(false, $header->statusMessage, $header->statusMessage);
        }
        return new PaymentProviderResult($body->getStatus(), $body->getStatusMessage(), $body->getMessage());
    }

    public function withDraw(Money $amount, $idTransaction)
    {
        throw new \BadFunctionCallException();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function call($data, $action, $method)
    {
        // TODO: Implement call() method.
    }

    public function type()
    {
        return new PaymentSelectorType(PaymentSelectorType::CREDIT_CARD_METHOD);
    }

    /**
     * @return PaymentCountry
     */
    public function getPaymentCountry()
    {
        return $this->paymentCountry;
    }

    /**
     * @return PaymentWeight
     */
    public function getPaymentWeight()
    {
        return $this->paymentWeight;
    }

    public function getName()
    {
        return PaymentProviderEnum::ROYALPAY;
    }
}
