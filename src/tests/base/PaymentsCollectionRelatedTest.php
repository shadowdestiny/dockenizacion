<?php


namespace EuroMillions\tests\base;


use EuroMillions\shared\components\PaymentsCollection;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\MoneyMatrixPaymentStrategy;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentStrategy;

trait PaymentsCollectionRelatedTest
{

    protected function getPaymentsCollectionTest()
    {
        $paymentsCollection = new PaymentsCollection();
        $paymentsCollection->addItem('WideCardPaymentStrategy',new WideCardPaymentStrategy());
        $paymentsCollection->addItem('MoneyMatrixPaymentStrategy',new MoneyMatrixPaymentStrategy());
        $paymentsCollection->addItem('FakePaymentStrategy',new FakeCardPaymentStrategy());
        return $paymentsCollection;
    }

}