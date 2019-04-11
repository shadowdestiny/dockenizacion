<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 11:04
 */

namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\interfaces\IPaymentResponseRedirect;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixConfig;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixGatewayClientWrapper;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;
use EuroMillions\web\vo\PaymentWeight;

class MoneyMatrixPaymentProvider implements ICardPaymentProvider, IHandlerPaymentGateway
{
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
        $this->paymentCountry = $config->getFilterConfig()->getCountries();
        $this->paymentWeight = $config->getFilterConfig()->getWeight();
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

    public function  getName()
    {
        return PaymentProviderEnum::MONEYMATRIX;
    }

    /**
     * @return IPaymentResponseRedirect
     */
    public function getResponseRedirect()
    {
        // TODO: Implement getResponseRedirect() method.
    }
}