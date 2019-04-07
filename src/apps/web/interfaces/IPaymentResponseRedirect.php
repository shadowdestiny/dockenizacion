<?php


namespace EuroMillions\web\interfaces;


interface IPaymentResponseRedirect
{
    public function redirectTo(...$params);
}