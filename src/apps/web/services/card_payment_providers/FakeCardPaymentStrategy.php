<?php


namespace EuroMillions\web\services\card_payment_providers;


class FakeCardPaymentStrategy implements ICreditCardStrategy
{

    public function get()
    {
        return new FakeCardPaymentProvider();
    }
}