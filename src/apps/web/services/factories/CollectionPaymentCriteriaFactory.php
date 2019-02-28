<?php


namespace EuroMillions\web\services\factories;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\vo\enum\PaymentSelectorType;

class CollectionPaymentCriteriaFactory
{

    public static function createCollectionFromSelectorCriteria(PaymentsCollection $paymentsCollection, PaymentSelectorType $paymentSelectorType)
    {
        return (new CriteriaSelector($paymentSelectorType))->meetCriteria($paymentsCollection);
    }


}