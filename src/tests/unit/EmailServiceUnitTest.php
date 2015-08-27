<?php
namespace tests\unit;

use EuroMillions\entities\User;
use EuroMillions\vo\Email;
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
        $this->markTestIncomplete();
        $user = new User();
        $user->initialize([
            'email' => new Email('a@a.com'),
            'name'  => 'Antonio',
            'surname' => 'Hernández'
        ]);
        $url = 'lasdfslsdklhfa';
        $authService_double = $this->getServiceDouble('AuthService');
        $mailService_double = $this->getServiceDouble('MailService');
        $authService_double->getValidationUrl($user)->willReturn($url);
        $sut = $this->getDomainServiceFactory()->getEmailService($mailService_double->reveal(), $authService_double->reveal());
        $sut->sendRegistrationMail($user);

    }
}