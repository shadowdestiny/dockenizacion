<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use EuroMillions\tests\base\UnitTestBase;

class WebLanguageStrategyUnitTest extends UnitTestBase
{
    protected $session_double;
    protected $request_double;

    public function setUp()
    {
        parent::setUp();
        $this->session_double = $this->prophesize('\Phalcon\Session\AdapterInterface');
        $this->request_double = $this->getInterfaceDouble('IRequest');
    }

    /**
     * method set
     * when called
     * should passParamToSession
     */
    public function test_set_called_passParamToSession()
    {
        $language = 'azofaifa';

        $this->session_double->set(WebLanguageStrategy::LANGUAGE_VAR, $language)->shouldBeCalled();

        $sut = $this->getSut();
        $sut->set($language);
    }

    /**
     * method get
     * when calledAndSessionHasValue
     * should returnSessionValue
     */
    public function test_get_calledAndSessionHasValue_returnSessionValue()
    {
        $language_in_session = 'azofaifa';
        $this->session_double->has(WebLanguageStrategy::LANGUAGE_VAR)->willReturn(true);
        $this->session_double->get(WebLanguageStrategy::LANGUAGE_VAR)->willReturn($language_in_session);
        $sut = $this->getSut();
        $actual = $sut->get();
        $this->assertEquals($language_in_session, $actual);
    }

    /**
     * method get
     * when calledAndSessionHasNoValue
     * should returnRequestBestLanguageAndSetInSession
     */
    public function test_get_calledAndSessionHasNoValue_returnRequestBestLanguageAndSetInSession()
    {
        $language = 'en';
        $this->session_double->has(WebLanguageStrategy::LANGUAGE_VAR)->willReturn(false);
        $this->request_double->getBestLanguage()->willReturn($language);
        $this->session_double->set(WebLanguageStrategy::LANGUAGE_VAR, $language)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->get();
        $this->assertEquals($language, $actual);
    }

    /**
     * method get
     * when calledAndNeitherSessionNorRequestHasTheValue
     * should returnEnAsDefaultValueAndSetInSession
     */
    public function test_get_calledAndNeitherSessionNorRequestHasTheValue_returnEnAsDefaultValueAndSetInSession()
    {
        $expected = 'en';
        $this->session_double->has(WebLanguageStrategy::LANGUAGE_VAR)->willReturn(false);
        $this->request_double->getBestLanguage()->willReturn(null);
        $this->session_double->set(WebLanguageStrategy::LANGUAGE_VAR, $expected)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->get();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @return WebLanguageStrategy
     */
    protected function getSut()
    {
        $sut = new WebLanguageStrategy($this->session_double->reveal(), $this->request_double->reveal());
        return $sut;
    }
}