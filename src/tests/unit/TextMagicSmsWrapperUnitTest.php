<?php


namespace tests\unit;


use EuroMillions\web\components\TextMagicSmsWrapper;
use tests\base\UnitTestBase;
use TextMagicSMS\Exception\LowBalanceException;
use TextMagicSMS\Exception\WrongPhoneFormatException;

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
        $config_data['number_phones'] = ['34626966592'];
        $this->textMagicAPI_double->send($config_data['message'], $config_data['number_phones'], $config_data['is_unicode'])->shouldBeCalled();
        $this->exercisePrepareSMS($config_data);
    }

    /**
     * method send
     * when called
     * should throwWrongPhoneFormatException
     */
    public function test_send_called_throwWrongPhoneFormatException()
    {
        $config_data = $this->getDataToSendSMS();
        $config_data['number_phones'] = ['966592','626966592'];
        $this->textMagicAPI_double->send($config_data['message'], $config_data['number_phones'], $config_data['is_unicode'])->willThrow(new WrongPhoneFormatException());
        $this->setExpectedException('\Exception');
        $this->exercisePrepareSMS($config_data);
    }

    /**
     * method send
     * when called
     * should throwLowBalanceException
     */
    public function test_send_called_throwLowBalanceException()
    {
        $config_data = $this->getDataToSendSMS();
        $config_data['number_phones'] = ['34626966592'];
        $this->textMagicAPI_double->send($config_data['message'], $config_data['number_phones'], $config_data['is_unicode'])->willThrow(new LowBalanceException());
        $this->setExpectedException('\Exception');
        $this->exercisePrepareSMS($config_data);
    }



    private function getDataToSendSMS()
    {
        return [
              'username' => 'test',
              'password' => '123456',
              'message'  => 'Error updating result',
              'number_phones' => [
                  '',
              ],
              'is_unicode' => true
        ];
    }

    /**
     * @param $config_data
     */
    private function exercisePrepareSMS($config_data)
    {

        $revelation = $this->textMagicAPI_double->reveal();
        $sut = new TextMagicSmsWrapper([
            'username' => $config_data['username'],
            'password' => $config_data['password']
        ],
            $revelation);
        $sut->send($config_data['message'], $config_data['number_phones'], $config_data['is_unicode']);
    }
}