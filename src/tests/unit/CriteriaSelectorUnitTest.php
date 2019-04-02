<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\tests\base\PaymentsCollectionRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\vo\enum\PaymentSelectorType;

class CriteriaSelectorUnitTest extends UnitTestBase
{

    use PaymentsCollectionRelatedTest;
    /**
     * method meetCriteria
     * when called
     * should returnProperPaymentInCollection
     */
    public function test_meetCriteria_called_returnProperPaymentInCollection()
    {
        $expected = new PaymentsCollection();
        $expected->addItem('WideCardPaymentStrategy',new WideCardPaymentStrategy());
        $expected->addItem('FakePaymentStrategy',new FakeCardPaymentStrategy());
        $paymentsCollection = $this->getPaymentsCollectionTest();
        $actual = $this->getSut(new PaymentSelectorType(PaymentSelectorType::CREDIT_CARD_METHOD))->meetCriteria($paymentsCollection);
        $this->assertEquals($expected,$actual);
    }

    private function getSut(PaymentSelectorType $paymentSelectorType)
    {
        return new CriteriaSelector(
            new PaymentSelectorType($paymentSelectorType)
        );
    }


}