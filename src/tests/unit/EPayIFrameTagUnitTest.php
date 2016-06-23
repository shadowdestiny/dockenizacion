<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\components\tags\EPayIframeTag;

class EPayIFrameTagUnitTest extends UnitTestBase
{


    /**
     * method render
     * when called
     * should returnAnIframeWithHtmlCode
     */
    public function test_render_called_returnAnIframeWithHtmlCode()
    {
        $params = [
            'mobile' => 0,
            'order_reference' => '123456',
            'order_currency' => 'EUR',
            'amount' => '5.00',
            'qty' => '1',
            'form' => 'purchase'
        ];
        $config = [
            'client_id' => 815623,
            'form_purchase_id' => 2873,
            'test_transaction' => 1,
            'md5Key' => '3TgJ2CqehZ',
            'url' => 'https://payment-b30bb144.emppay.com/'
        ];
        $actual = EPayIframeTag::render($params,$config);
        $this->assertContains('form_id=2873',$actual);
    }


    /**
     * method render
     * when calledWithDepositForm
     * should containCorrectlyFormId
     */
    public function test_render_calledWithDepositForm_containCorrectlyFormId()
    {
        $params = [
            'mobile' => 0,
            'order_reference' => '123456',
            'order_currency' => 'EUR',
            'amount' => '5.00',
            'qty' => '1',
            'form' => 'deposit'
        ];
        $config = [
            'client_id' => 815623,
            'form_deposit_id' => 2883,
            'test_transaction' => 1,
            'md5Key' => '3TgJ2CqehZ',
            'url' => 'https://payment-b30bb144.emppay.com/'
        ];
        $actual = EPayIframeTag::render($params,$config);
        $this->assertContains('form_id=2883',$actual);
    }

}