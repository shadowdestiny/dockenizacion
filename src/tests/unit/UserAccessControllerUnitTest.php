<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\controllers\UserAccessController;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class UserAccessControllerControllerBaseUnitTest extends UnitTestBase
{
    protected $authService_stub;
    protected $geoService_stub;
    protected $session_double;
    protected $request_double;


    public function setUp()
    {
        $this->authService_stub = $this->getServiceDouble('AuthService');
        $this->geoService_stub = $this->getServiceDouble('GeoService');
        parent::setUp();
    }
    /**
     * method signUpAction
     * when calledWithInvalidPassword
     * should renderProperError
     * @dataProvider getWrongPasswords
     */
    public function test_signUpAction_calledWithInvalidPassword_renderProperError($password)
    {
        $this->markTestSkipped('Se ha añadido el WebLanguageStrategy para las traducciones y se tiene q mirar');
        $request_stub = $this->prophesize('Phalcon\HTTP\RequestInterface');
        $request_stub->isPost()->willReturn(true);
        $request_stub->getPost()->willReturn(
            [
                'name' => 'correct name',
                'surname' => 'correct surname',
                'email' => 'correct@email.com',
                'password' => $password,
                'confirm_password' => $password,
                'country' => 'correct country',
            ]
        );
        $request_stub->getPost(Argument::type('string'))->willReturn(null);
        $this->stubDiService('request', $request_stub->reveal());
        $this->authService_stub->register(Argument::any())->shouldNotBeCalled();
        $this->checkViewVarsContain('errors', ['Your password should be composed of at least six characters.']);
        $this->geoService_stub->countryList()->willReturn(array('Spain'));
        $sut = $this->getSut();
        $sut->signUpAction();
    }

    public function getWrongPasswords()
    {
        return [
            ['1234'],
            ['1'],
            ['W'],
            ['Wro']
        ];
    }

    /**
     * @return UserAccessController
     */
    private function getSut()
    {
        $sut = new UserAccessController();
        $sut->initialize($this->authService_stub->reveal(), $this->geoService_stub->reveal());
        return $sut;
    }
}