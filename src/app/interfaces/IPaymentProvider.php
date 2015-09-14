<?php


namespace EuroMillions\interfaces;

use Money\Money;

interface IPaymentProvider
{
    public function doPayment(Money $amount);
}