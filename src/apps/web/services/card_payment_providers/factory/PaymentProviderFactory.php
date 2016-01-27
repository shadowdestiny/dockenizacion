<?php

namespace EuroMillions\web\services\card_payment_providers\factory;

use EuroMillions\web\services\card_payment_providers\ICreditCardStrategy;

//EMTD we should move of namespace discuss with Antonio
class PaymentProviderFactory
{

    public function getCreditCardPaymentProvider( ICreditCardStrategy $paymentProviderStategy )
    {
        return $paymentProviderStategy->get();
    }

}