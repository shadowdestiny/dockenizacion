<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 11:04
 */

namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixConfig;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixGatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\shared\CountriesCollection;
use EuroMillions\web\vo\dto\PaymentProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;

class MoneyMatrixPaymentProvider implements ICardPaymentProvider, IHandlerPaymentGateway
{

    use CountriesCollection;

    protected $gatewayClient;

    protected $config;

    /**
     * @var PaymentCountry $paymentCountry
     */
    protected $paymentCountry;

    protected $paymentWeight;



    public function __construct(MoneyMatrixConfig $config, $gateway = null)
    {
        $this->gatewayClient = $gateway ?: new MoneyMatrixGatewayClientWrapper($config);
        $this->config = $config;
        $this->paymentCountry = new PaymentCountry($this->countries());
        $this->paymentWeight= new PaymentWeight(90);
    }

    /**
     * @param PaymentProviderDTO $data
     * @return void
     */
    public function charge(PaymentProviderDTO $data)
    {
        throw new \BadFunctionCallException();
    }

    /**
     * @return MoneyMatrixConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function call($data,$action,$method)
    {
        try {
            return $this->gatewayClient->send($data,$action,$method);
        } catch ( \Exception $e ) {
            throw new \Exception($e->getMessage());
        }

    }

    public function type()
    {
        return new PaymentSelectorType(PaymentSelectorType::OTHER_METHOD);
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
}