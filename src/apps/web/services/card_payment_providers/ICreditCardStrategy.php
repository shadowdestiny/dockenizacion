<?php


namespace EuroMillions\web\services\card_payment_providers;


interface ICreditCardStrategy
{
    public function get();
}