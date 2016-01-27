<?php

namespace EuroMillions\web\services\card_payment_providers\factory;

use EuroMillions\web\services\card_payment_providers\ICreditCardStrategy;

class PaymentProviderFactory
{

    public function getPaymentProvider( ICreditCardStrategy $paymentProviderStategy )
    {
        return $paymentProviderStategy->get();
    }

}