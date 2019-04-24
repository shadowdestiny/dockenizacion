<?php


namespace EuroMillions\shared\components\builder;

use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\Order;
use Money\Money;

final class PaymentProviderDTOBuilder
{
    private $provider;
    private $user;
    private $order;
    private $amount;
    private $card;

    private $mapper = [
        PaymentProviderEnum::ROYALPAY => 'EuroMillions\web\vo\dto\payment_provider\\RoyalPayPaymentProviderDTO',
        PaymentProviderEnum::WIRECARD => 'EuroMillions\web\vo\dto\payment_provider\\WireCardPaymentProviderDTO',
        PaymentProviderEnum::FAKECARD => 'EuroMillions\web\vo\dto\payment_provider\\FakeCardPaymentProviderDTO',
        PaymentProviderEnum::PAYXPERT => 'EuroMillions\web\vo\dto\payment_provider\\PayXpertPaymentProviderDTO',
        PaymentProviderEnum::EMERCHANT => 'EuroMillions\web\vo\dto\payment_provider\\eMerchantPaymentProviderDTO',
    ];


    public function __construct()
    {

    }

    public function build()
    {
        $paymentProviderDTO = $this->mapper[$this->provider->getName()];

        return new $paymentProviderDTO(
            $this->user,
            $this->order,
            $this->amount,
            $this->card,
            $this->provider
        );
    }

    /**
     * @param ICardPaymentProvider $provider
     * @return PaymentProviderDTOBuilder
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * @param UserDTO $user
     * @return PaymentProviderDTOBuilder
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Order $order
     * @return PaymentProviderDTOBuilder
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param Money $amount
     * @return PaymentProviderDTOBuilder
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param CreditCard $card
     * @return PaymentProviderDTOBuilder
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }
}