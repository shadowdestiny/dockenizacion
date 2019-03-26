<?php


namespace EuroMillions\web\services\factories;

use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\shared\enums\PaymentProviderEnum;
use EuroMillions\web\interfaces\IPaymentsCriteria;
use EuroMillions\web\services\criteria_strategies\AndCriteria;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\services\criteria_strategies\NameCriteria;
use EuroMillions\web\vo\enum\PaymentSelectorType;

class CollectionPaymentCriteriaFactory
{
    public static function createCollectionFromSelectorCriteria(PaymentsCollection $paymentsCollection, PaymentSelectorType $paymentSelectorType)
    {
        return (new CriteriaSelector($paymentSelectorType))->meetCriteria($paymentsCollection);
    }

    public static function createCollectionFromSelectorCriteriaAndOtherCriteria(PaymentsCollection $paymentsCollection, CriteriaSelector $criteriaSelector, IPaymentsCriteria $criteria)
    {
        return  (new AndCriteria($criteriaSelector, $criteria))->meetCriteria($paymentsCollection);
    }

    public static function createADefaultPaymentProviderByName(PaymentsCollection $paymentsCollection,PaymentProviderEnum $providerName)
    {
        return $paymentsCollection->getItemByName($providerName);
    }
}
