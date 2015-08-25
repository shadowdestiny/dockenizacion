<?php
namespace tests\unit;

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
        $authService_double = $this->getServiceDouble('AuthService');
        $mailService_double = $this->getServiceDouble('MailService');
        $sut = $this->getDomainServiceFactory()->getEmailService($mailService_double->reveal(), $authService_double->reveal());
        $sut->sendRegistrationMail($user);
    }
}