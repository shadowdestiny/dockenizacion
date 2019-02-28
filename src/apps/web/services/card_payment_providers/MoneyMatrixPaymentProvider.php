<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 11:04
 */

namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IHandlerPaymentGateway;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixConfig;
use EuroMillions\web\services\card_payment_providers\moneymatrix\MoneyMatrixGatewayClientWrapper;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use Money\Money;

class MoneyMatrixPaymentProvider implements ICardPaymentProvider, IHandlerPaymentGateway
{

    protected $gatewayClient;

    protected $config;

    protected $data;



    public function __construct(MoneyMatrixConfig $config, $gateway = null)
    {
        $this->gatewayClient = $gateway ?: new MoneyMatrixGatewayClientWrapper($config);
        $this->config = $config;
    }

    /**
     * @param Money $amount
     * @param CreditCard $card
     * @return PaymentProviderResult
     */
    public function charge(Money $amount, CreditCard $card)
    {
        throw new \BadFunctionCallException();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function user(User $user)
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
}