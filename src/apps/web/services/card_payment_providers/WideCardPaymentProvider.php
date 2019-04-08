<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;
use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;
use EuroMillions\web\services\card_payment_providers\widecard\dto\WirecardBodyResponse;
use EuroMillions\web\services\card_payment_providers\widecard\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\widecard\redirect_response\WirecardRedirectResponseStrategy;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;
use Money\Money;
use Phalcon\Http\Client\Response;

class WideCardPaymentProvider implements ICardPaymentProvider,IHandlerPaymentGateway
{

    use CountriesCollection;

    private $gatewayClient;

    /**
     * @var WideCardConfig $config
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

    /**
     * @var WirecardRedirectResponseStrategy
     */
    protected $responseRedirect;

    public function __construct(WideCardConfig $config, $gatewayClient = null)
    {
        $this->gatewayClient = $gatewayClient ?: new GatewayClientWrapper($config);
        $this->config = $config;
        $this->paymentCountry = new PaymentCountry($this->countries());
        $this->paymentWeight= new PaymentWeight(50);
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
        $result = $this->gatewayClient->send($params);
        $header = $result->header;
        $body = WirecardBodyResponse::create(json_decode($result->body), $header->statusMessage);
        if( $header->statusCode != 200 ) {
            return new PaymentProviderResult(false,$body);
        }
        return new PaymentProviderResult($body->getStatus(), $body);

    }

    public function withDraw(Money $amount, $idTransaction)
    {
        $result = $this->gatewayClient->send(['idTransaction' => $idTransaction[0]['transactionID'],
                                              'amount' => $amount->getAmount() ]);

        //TODO: in this case, is called from admin only
        return $result;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function call($data,$action,$method)
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

    public function  getName()
    {
        return PaymentProviderEnum::WIRECARD;
    }

}