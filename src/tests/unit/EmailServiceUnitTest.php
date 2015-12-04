<?php
namespace tests\unit;

use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Url;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class EmailServiceUnitTest extends UnitTestBase
{
    private $authService_double;
    private $mailServiceApi_double;

    public function setUp()
    {
        parent::setUp();
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->mailServiceApi_double = $this->getInterfaceWebDouble('IMailServiceApi');
    }

    /**
     * method sendRegistrationEmail
     * when called
     * should sendProperArgumentsToMailService
     */
    public function test_sendRegistrationEmail_called_sendProperArgumentsToMailService()
    {
        $user = $this->getUser();
        $url_address = 'http://www.lasdfslsdklhfa.cat';
        $url = new Url($url_address);
        $expected_recipient_data = $this->getExpectedRecipientData();
        $expected_mail_data = [
            [
                'name' => 'title',
                'content' => 'Validate your email',
            ],
            [
                'name' => 'subtitle',
                'content' => 'We need you to validate your email.',
            ],
            [
                'name' => 'main',
                'content' => 'Thank you for registering an account with us. Please valudate the email address you regitered with by clicking on the link below:<br>
            <a href="' . $url->toNative() . '">Click this link to validate your registration</a>
            <br><br>or copy and paste this url in your browser:<br><span style="font-size:12px;">'.$url->toNative().'</span>'
                ,
            ],
        ];
        $this->authService_double->getValidationUrl($user)->willReturn($url);
        $this->mailServiceApi_double->send(Argument::type('string'), Argument::type('string'), $expected_recipient_data, Argument::type('string'), '', $expected_mail_data, [], Argument::type('string'), [])->shouldBeCalled();
        $sut = $this->getSut();
        $sut->sendRegistrationMail($user, $url);
    }

    /**
     * method sendPasswordResetMail
     * when called
     * should sendProperArgumentsToMailService
     */
    public function test_sendPasswordResetMail_called_sendProperArgumentsToMailService()
    {
        $user = $this->getUser();
        $url_address = 'http://www.lasdfslsdklhfa.cat';
        $url = new Url($url_address);
        $expected_recipient_data = $this->getExpectedRecipientData();
        $expected_mail_data = [
            [
                'name' => 'title',
                'content' => 'Password reset',
            ],
            [
                'name' => 'subtitle',
                'content' => 'Somebody has asked to reset your password.',
            ],
            [
                'name' => 'main',
                'content' => 'If it was you, you just have to <a href="'.$url_address.'">click this link to reset your password</a> <br>or copy and paste this url in your browser:<br>'.$url_address.'<br>If you didn\'t ask for the password reset, just ignore this email',
            ],

        ];
        $this->authService_double->getPasswordResetUrl($user)->willReturn($url);
        $this->mailServiceApi_double->send(Argument::type('string'), Argument::type('string'), $expected_recipient_data, Argument::type('string'), '', $expected_mail_data, [], Argument::type('string'), [])->shouldBeCalled();
        $sut = $this->getSut();
        $sut->sendPasswordResetMail($user, $url);
    }

    /**
     * @return \EuroMillions\services\EmailService
     */
    private function getSut()
    {
        $sut = $this->getDomainServiceFactory()->getServiceFactory()->getEmailService($this->mailServiceApi_double->reveal());
        return $sut;
    }

    /**
     * @return User
     */
    private function getUser()
    {
        $user = new User();
        $user->initialize([
            'email'   => new Email('a@a.com'),
            'name'    => 'Antonio',
            'surname' => 'Hernández'
        ]);
        return $user;
    }

    /**
     * @return array
     */
    private function getExpectedRecipientData()
    {
        $expected_recipient_data = [
            [
                'email' => 'a@a.com',
                'name'  => 'Hernández, Antonio',
                'type'  => 'to'
            ]
        ];
        return $expected_recipient_data;
    }
}