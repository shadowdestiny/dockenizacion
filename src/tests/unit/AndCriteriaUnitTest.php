<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\tests\base\PaymentsCollectionRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy;
use EuroMillions\web\services\criteria_strategies\AndCriteria;
use EuroMillions\web\services\criteria_strategies\CountryCriteria;
use EuroMillions\web\services\criteria_strategies\CriteriaSelector;
use EuroMillions\web\vo\enum\CountrySelectorType;
use EuroMillions\web\vo\enum\PaymentSelectorType;
use EuroMillions\web\vo\PaymentCountry;

class AndCriteriaUnitTest extends UnitTestBase
{

    use PaymentsCollectionRelatedTest;


    /**
     * method meetCriteria
     * when calledWithSelectorCriteriaAndCountryCriteria
     * should returnProperCollectionWithItems
     */
    public function test_meetCriteria_calledWithSelectorCriteriaAndCountryCriteria_returnProperCollectionWithItems()
    {
        $expected = new PaymentsCollection();
        $expected->addItem('WideCardPaymentStrategy',new WideCardPaymentStrategy());
        $collection = $this->getPaymentsCollectionTest();
        $actual = $this->getSut( PaymentSelectorType::CREDIT_CARD_METHOD, [CountrySelectorType::SPAIN])->meetCriteria($collection);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method meetCriteria
     * when calledWithSelectorCriteriaAndCountryCriteriaAndNoMatching
     * should returnEmptyCollection
     */
    public function test_meetCriteria_calledWithSelectorCriteriaAndCountryCriteriaAndNoMatching_returnEmptyCollection()
    {
        $expected = new PaymentsCollection();
        $collection = $this->getPaymentsCollectionTest();
        $actual = $this->getSut( PaymentSelectorType::CREDIT_CARD_METHOD, [CountrySelectorType::ALL_COUNTRIES])->meetCriteria($collection);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method meetCriteria
     * when calledWithSelectorCriteriaAndCountryWithMultiplesMatchingForRussiaCountry
     * should returnFakeProviderInFirstCollectionPosition
     */
    public function test_meetCriteria_calledWithSelectorCriteriaAndCountryWithMultiplesMatchingForRussiaCountry_returnFakeProviderInFirstCollectionPosition()
    {
        $collection = $this->getPaymentsCollectionTest();
        $actual = $this->getSut( PaymentSelectorType::CREDIT_CARD_METHOD, [CountrySelectorType::RUSSIA])->meetCriteria($collection)->getIterator()->current();
        $this->assertInstanceOf(FakeCardPaymentStrategy::class,$actual);
    }
    

    private function getSut($method, $country)
    {
        return new AndCriteria(
            new CriteriaSelector(new PaymentSelectorType($method)),
            new CountryCriteria(new PaymentCountry($country))
        );
    }


}