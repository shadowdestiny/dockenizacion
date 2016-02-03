<?php
namespace tests\unit;

use EuroMillions\web\controllers\UserAccessController;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class UserAccessControllerControllerBaseUnitTest extends UnitTestBase
{
    protected $authService_stub;
    protected $geoService_stub;


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
        $this->checkViewVarsContain('errors', ['The password should have at least one number, a lowercase and uppercase character.']);
        $this->geoService_stub->countryList()->willReturn(array('Spain'));
        $sut = $this->getSut();
        $sut->signUpAction();
    }

    public function getWrongPasswords()
    {
        return [
            ['wrong password'],
            ['wrong01'],
            ['WRONG01'],
            ['W R 01']
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