<?php


namespace EuroMillions\web\services\card_payment_providers;


use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\gcp\GatewayClientGCP;
use EuroMillions\web\services\card_payment_providers\gcp\GCPConfig;
use EuroMillions\web\vo\CreditCard;
use Money\Money;

class GCPCardPaymentProvider implements ICardPaymentProvider
{

    protected $gatewayClient;
    protected $config;
    protected $order;

    public function __construct(GCPConfig $config,$gatewayClient = null)
    {
        $this->config = $config;
        $this->gatewayClient = $gatewayClient ?: new GatewayClientGCP($config->getUrl(),$config->getMerchantId(),$config->getVisaGateway(),$config->getVisaSignKey());
    }


    /**
     * @param Money $amount
     * @param CreditCard $card
     * @return PaymentProviderResult
     * @throws \Exception
     */
    public function charge(Money $amount, CreditCard $card)
    {
        $this->gatewayClient->setCreditCard($card);
        $this->gatewayClient->renewConfig($this->config);
        $this->gatewayClient->setAmount($amount);
        return $this->gatewayClient->send();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function user(User $user)
    {
        $this->gatewayClient->setUser($user);
    }
}