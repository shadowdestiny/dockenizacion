<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\MoneyMatrixPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\vo\enum\PaymentSelectorType;

class CriteriaSelectorUnitTest extends UnitTestBase
{

    /**
     * method meetCriteria
     * when called
     * should returnProperPaymentInCollection
     */
    public function test_meetCriteria_called_returnProperPaymentInCollection()
    {
        $expected = new PaymentsCollection();
        $expected->addItem('WideCardPaymentStrategy',new WideCardPaymentStrategy());
        $paymentsCollection = new PaymentsCollection();
        $paymentsCollection->addItem('WideCardPaymentStrategy',new WideCardPaymentStrategy());
        $paymentsCollection->addItem('MoneyMatrixPaymentStrategy',new MoneyMatrixPaymentStrategy());
        $actual = $this->getSut(new PaymentSelectorType(PaymentSelectorType::CREDIT_CARD_METHOD));
        $this->assertEquals($expected,$actual->meetCriteria($paymentsCollection));
    }

    private function getSut(PaymentSelectorType $paymentSelectorType)
    {
        return new CriteriaSelector(
            new PaymentSelectorType($paymentSelectorType)
        );
    }


}