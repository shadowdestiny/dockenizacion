<?php
namespace EuroMillions\web\interfaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\shared\vo\results\PaymentProviderResult;
use EuroMillions\web\vo\dto\payment_provider\PaymentProviderDTO;
use Money\Money;

interface ICardPaymentProvider
{
    /**
     * @param PaymentProviderDTO $data
     * @return PaymentProviderResult
     */
    public function charge(PaymentProviderDTO $data);

    /**
     * @return string
     */
    public function getName();
}