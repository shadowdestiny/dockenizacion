<?php


namespace EuroMillions\interfaces;


interface IPaymentMethod
{
    public function charge($amount);
}