<?php


namespace tests\unit;


use EuroMillions\web\components\TextMagicSmsWrapper;
use tests\base\UnitTestBase;

class TextMagicSmsWrapperUnitTest extends UnitTestBase
{
    private $textMagicAPI_double;

    public function setUp()
    {
        parent::setUp();
        $this->textMagicAPI_double = $this->prophesize('TextMagicSMS\TextMagicAPI');
    }

    /**
     * method send
     * when called
     * should passProperParamsToTextMagicApi
     */
    public function test_send_called_passProperParamsToTextMagicApi()
    {
        $config_data = $this->getDataToSendSMS();
        $this->textMagicAPI_double->send($config_data['message'],$config_data['number_phones'],$config_data['is_unicode'])->shouldBeCalled();
        $revelation = $this->textMagicAPI_double->reveal();
        $sut = new TextMagicSmsWrapper([
                                        'username' => $config_data['username'],
                                        'password' => $config_data['password']
                                        ],
            $revelation);
        $sut->send($config_data['message'],$config_data['number_phones'],$config_data['is_unicode']);
    }

    /**
     * method send
     * when called
     * should sendSMS
     */
    public function test_send_called_sendSMS()
    {

    }


    private function getDataToSendSMS()
    {
        return [
              'username' => 'test',
              'password' => '123456',
              'message'  => 'Error updating result',
              'number_phones' => [
                  '34626966592',
              ],
              'is_unicode' => true
        ];
    }
}