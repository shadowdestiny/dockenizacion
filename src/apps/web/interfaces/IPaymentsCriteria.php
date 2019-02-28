<?php


namespace EuroMillions\web\interfaces;


use EuroMillions\shared\components\PaymentsCollection;

interface IPaymentsCriteria
{
    public function meetCriteria(PaymentsCollection $paymentsCollection);
}