<?php


namespace EuroMillions\web\interfaces;

use Money\Money;

interface IPaymentProvider
{
    public function doPayment(Money $amount);
}