<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\tests\base\PaymentsCollectionRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\MoneyMatrixPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy;
use EuroMillions\web\services\criteria_strategies\CountryCriteria;
use EuroMillions\web\vo\enum\CountrySelectorType;
use EuroMillions\web\vo\PaymentCountry;

class CountryCriteriaUnitTest extends UnitTestBase
{
    use PaymentsCollectionRelatedTest;


    /**
     * method meetCriteria
     * when called
     * should returnCollectionWithProperData
     */
    public function test_meetCriteria_called_returnCollectionWithProperData()
    {
        $expected = new PaymentsCollection();
        $expected->addItem('WideCardPaymentStrategy',new WideCardPaymentStrategy());
        $expected->addItem('MoneyMatrixPaymentStrategy',new MoneyMatrixPaymentStrategy());
        $paymentsCollection = $this->getPaymentsCollectionTest();
        $actual = $this->getSut([CountrySelectorType::SPAIN])->meetCriteria($paymentsCollection);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method meetCriteria
     * when calledWithSelectorCriteriaAndCountryWithMultiplesMatchingForRussiaCountry
     * should returnFakeProviderInFirstCollectionPosition
     */
    public function test_meetCriteria_calledWithSelectorCriteriaAndCountryWithMultiplesMatchingForRussiaCountry_returnFakeProviderInFirstCollectionPosition()
    {
        $paymentsCollection = $this->getPaymentsCollectionTest();
        $actual = $this->getSut([CountrySelectorType::RUSSIA])->meetCriteria($paymentsCollection)->getIterator()->current();
        $this->assertInstanceOf(FakeCardPaymentStrategy::class,$actual);
    }


    private function getSut($country)
    {
        return new CountryCriteria(
            new PaymentCountry($country)
        );
    }

}