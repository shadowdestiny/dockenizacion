<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\components\MandrillWrapper;
use EuroMillions\tests\base\UnitTestBase;

class MandrillWrapperUnitTest extends UnitTestBase
{
    /**
     * method send
     * when called
     * should passProperParamsToMandrill
     */
    public function test_send_called_passProperParamsToMandrill()
    {
        $this->markTestSkipped('we use Postmark provider');
        $template_name = 'template name';
        $template_vars = ['template', 'vars'];
        list($from_name, $from_email, $to, $subject, $html, $global_vars, $recipient_vars, $api_key, $message) = $this->getMessageArray();

        list($mandrill_double_revelation, $messages_double) = $this->getMessagesDouble();

        $messages_double->sendTemplate($template_name, $template_vars, $message)->shouldBeCalled();
        $mandrill_double_revelation->messages = $messages_double->reveal();

        $sut = new MandrillWrapper($api_key, $mandrill_double_revelation);
        $sut->send($from_name, $from_email, $to, $subject, $html, $global_vars, $recipient_vars, $template_name, $template_vars);
    }

    /**
     * method send
     * when calledWithoutTemplate
     * should callSendMethod
     */
    public function test_send_calledWithoutTemplate_callSendMethod()
    {
        $this->markTestSkipped('we use Postmark provider');
        list($from_name, $from_email, $to, $subject, $html, $global_vars, $recipient_vars, $api_key, $message) = $this->getMessageArray();
        list($mandrill_double_revelation, $messages_double) = $this->getMessagesDouble();
        $messages_double->send($message)->shouldBeCalled();
        $mandrill_double_revelation->messages = $messages_double->reveal();
        $sut = new MandrillWrapper($api_key, $mandrill_double_revelation);
        $sut->send($from_name, $from_email, $to, $subject, $html, $global_vars, $recipient_vars);
    }

    /**
     * @return array
     */
    private function getMessageArray()
    {
        $from_name = 'Euromillions.com registration service';
        $from_email = 'no-reply@euromillions.com';
        $to = ['user@user.com'];
        $subject = 'subject';
        $html = 'html';
        $global_vars = ['global', 'vars'];
        $recipient_vars = ['recipient', 'vars'];
        $api_key = 'api key';

        $message = [
            'html'                => $html,
            'subject'             => $subject,
            'from_email'          => $from_email,
            'from_name'           => $from_name,
            'to'                  => $to,
            'important'           => true,
            'track_opens'         => true,
            'track_clicks'        => true,
            'auto_text'           => true,
            'preserve_recipients' => false,
            'global_merge_vars'   => $global_vars,
            'merge_vars'          => $recipient_vars,
        ];
        return array($from_name, $from_email, $to, $subject, $html, $global_vars, $recipient_vars, $api_key, $message);
    }

    /**
     * @return array
     */
    private function getMessagesDouble()
    {
        $mandrill_double = $this->prophesize('\Mandrill');
        $mandrill_double_revelation = $mandrill_double->reveal();
        $messages_double = $this->prophesize('Mandrill_Messages');
        return array($mandrill_double_revelation, $messages_double);
    }
}