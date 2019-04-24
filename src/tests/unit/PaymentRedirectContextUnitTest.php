<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\FakeCardPaymentProvider;
use EuroMillions\web\services\card_payment_providers\royalpay\RoyalPayConfig;
use EuroMillions\web\services\card_payment_providers\RoyalPayPaymentProvider;
use EuroMillions\web\services\card_payment_providers\shared\NormalRedirectResponseStrategy;
use EuroMillions\web\services\card_payment_providers\shared\PaymentRedirectContext;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use EuroMillions\web\services\card_payment_providers\WideCardPaymentProvider;

class PaymentRedirectContextSpy extends PaymentRedirectContext
{
    public function getStrategy()
    {
        return $this->strategy;
    }
}


class PaymentRedirectContextUnitTest extends UnitTestBase
{


    /**
     * method __construct
     * when called
     * should setWirecarRedirectResponsedAsStrategy
     */
    public function test___construct_called_setWirecarRedirectResponsedAsStrategy()
    {
        $sut = $this->getSut(
            new WideCardPaymentProvider(
                new WideCardConfig(
                    "",
                    ""
                )
            ),
            "EuroMillions"
        );
        $actual = $sut->getStrategy();
        $this->assertInstanceOf(
            'EuroMillions\web\services\card_payment_providers\widecard\redirect_response\WirecardRedirectResponseStrategy',
            $actual
        );

    }

    /**
     * method __construct
     * when calledWithRoyalPayPaymentProvider
     * should setRoyalPayRedirectResponseAsStrategy
     */
    public function test___construct_calledWithRoyalPayPaymentProvider_setRoyalPayRedirectResponseAsStrategy()
    {
        $sut = $this->getSut(
            new RoyalPayPaymentProvider(
                new RoyalPayConfig(
                    "",
                    ""
                )
            ),
            "EuroMillions"
        );
        $actual = $sut->getStrategy();
        $this->assertInstanceOf(
            'EuroMillions\web\services\card_payment_providers\royalpay\redirect_response\RoyalPayRedirectResponseStrategy',
            $actual
        );
    }

    /**
     * method __construct
     * when calledWithNormalPaymentProvider
     * should setRoyalPayRedirectResponseAsStrategy
     */
    public function test___construct_calledWithNormalPaymentProvider_setRoyalPayRedirectResponseAsStrategy()
    {
        $sut = new PaymentRedirectContextSpy(
            new FakeCardPaymentProvider(),
            "EuroMillions"
        );
        $actual = $sut->getStrategy();
        $this->assertInstanceOf(
            'EuroMillions\web\services\card_payment_providers\shared\NormalRedirectResponseStrategy',
            $actual
        );
    }

    /**
     * @return PaymentRedirectContextSpy
     */
    protected function getSut(ICardPaymentProvider $paymentProvider, $lotteryName)
    {
        $sut = new PaymentRedirectContextSpy(
            $paymentProvider,
            $lotteryName
        );
        return $sut;
    }


}