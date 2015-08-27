<?php
namespace tests\unit;

use EuroMillions\entities\User;
use EuroMillions\vo\Email;
use EuroMillions\vo\Url;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class EmailServiceUnitTest extends UnitTestBase
{
    /**
     * method sendRegistrationEmail
     * when called
     * should sendProperArgumentsToMailService
     */
    public function test_sendRegistrationEmail_called_sendProperArgumentsToMailService()
    {
        $user = new User();
        $user->initialize([
            'email'   => new Email('a@a.com'),
            'name'    => 'Antonio',
            'surname' => 'Hernández'
        ]);
        $url_address = 'http://www.lasdfslsdklhfa.cat';
        $url = new Url($url_address);
        $expected_recipient_data = [
            [
                'email' => 'a@a.com',
                'name'  => 'Hernández, Antonio',
                'type'  => 'to'
            ]
        ];
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
                'content' => '<a href="'.$url_address.'">Click here to validate you email!</a> <br>or copy and paste this url in your browser:<br>'.$url_address,
            ],
        ];
        $authService_double = $this->getServiceDouble('AuthService');
        $mailService_double = $this->getInterfaceDouble('IMailServiceApi');
        $authService_double->getValidationUrl($user)->willReturn($url);
        $mailService_double->send(Argument::type('string'), Argument::type('string'), $expected_recipient_data, Argument::type('string'), '', $expected_mail_data, [], Argument::type('string'), [])->shouldBeCalled();
        $sut = $this->getDomainServiceFactory()->getEmailService($mailService_double->reveal(), $authService_double->reveal());
        $sut->sendRegistrationMail($user);
    }
}