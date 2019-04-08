<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\web\services\card_payment_providers\shared\dto\PaymentBodyResponse;

interface IPaymentResponseRedirect
{
    public function redirectTo(PaymentBodyResponse $paymentBodyResponse);
}