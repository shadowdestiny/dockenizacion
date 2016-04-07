<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\components\PhalconUrlWrapper;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Url;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

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
        $this->markTestSkipped('Hay que revisar si y como se está enviando el registration email, y si este test sigue teniendo sentido');
        list($user, $url) = $this->exerciseEmail();
        $this->authService_double->getValidationUrl($user)->willReturn($url);
        $sut = $this->getSut();
        $sut->sendRegistrationMail($user, $url);
    }

    /**
     * method sendWelcomeEmail
     * when called
     * should sendProperEmailTemplate
     */
    public function test_sendWelcomeEmail_called_sendProperEmailTemplate()
    {
        list($user, $url) = $this->exerciseEmail();
        $sut = $this->getSut();
        $sut->sendWelcomeEmail($user,new PhalconUrlWrapper());
    }

    /**
     * method sendPasswordResetMail
     * when called
     * should sendProperArgumentsToMailService
     */
    public function test_sendPasswordResetMail_called_sendProperArgumentsToMailService()
    {
        list($user, $url) = $this->exerciseEmail();
        $this->authService_double->getPasswordResetUrl($user)->willReturn($url);
        $sut = $this->getSut();
        $sut->sendPasswordResetMail($user, $url);
    }

    /**
     * @return \EuroMillions\web\services\EmailService
     */
    private function getSut()
    {
        return new EmailService(
            $this->mailServiceApi_double->reveal(),
            $this->getDi()->get('config')['mail']
        );
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
    protected function exerciseEmail()
    {
        $user = $this->getUser();
        $url_address = 'http://www.lasdfslsdklhfa.cat';
        $url = new Url($url_address);
        $this->mailServiceApi_double->send(Argument::type('string'), Argument::type('string'), Argument::type('array'), Argument::type('string'), '', Argument::type('array'), [], Argument::type('string'), [])->shouldBeCalled();
        return array($user, $url);
    }
}