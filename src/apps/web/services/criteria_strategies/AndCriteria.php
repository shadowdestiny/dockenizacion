<?php


namespace EuroMillions\web\services\criteria_strategies;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\web\interfaces\IPaymentsCriteria;


class AndCriteria implements IPaymentsCriteria
{

    private $criteria;

    private $otherCriteria;

    public function __construct(IPaymentsCriteria $criteria, IPaymentsCriteria $otherCriteria)
    {
        $this->criteria= $criteria;
        $this->otherCriteria= $otherCriteria;
    }


    public function meetCriteria(PaymentsCollection $paymentsCollection)
    {
        $selectorPaymentsCollection = $this->criteria->meetCriteria($paymentsCollection);
        return $this->otherCriteria->meetCriteria($selectorPaymentsCollection);
    }
}