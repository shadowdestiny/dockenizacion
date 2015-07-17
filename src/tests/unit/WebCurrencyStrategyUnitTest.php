<?php
namespace tests\unit;

use EuroMillions\services\preferences_strategies\WebCurrencyStrategy;
use Money\Currency;
use tests\base\UnitTestBase;

class WebCurrencyStrategyUnitTest extends UnitTestBase
{
    private $session_double;
    public function setUp()
    {
        parent::setUp();
        $this->session_double = $this->prophesize('EuroMillions\interfaces\ISession');
    }

    /**
     * method get
     * when calledWithValueNotInSession
     * should returnDefaultValueEuro
     */
    public function test_get_calledWithValueNotInSession_returnDefaultValueEuro()
    {
        $this->session_double->has(WebCurrencyStrategy::CURRENCY_VAR)->willReturn(false);
        $sut = new WebCurrencyStrategy($this->session_double->reveal());
        $actual = $sut->get();
        $expected = new Currency('EUR');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method get
     * when calledWithValueInSession
     * should returnValueFromSession
     */
    public function test_get_calledWithValueInSession_returnValueFromSession()
    {
        $this->session_double->has(WebCurrencyStrategy::CURRENCY_VAR)->willReturn(true);
        $this->session_double->get(WebCurrencyStrategy::CURRENCY_VAR)->willReturn('USD');
        $sut = new WebCurrencyStrategy($this->session_double->reveal());
        $actual = $sut->get();
        $expected = new Currency('USD');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method set
     * when calledWithCurrency
     * should setCurrencyNameInSession
     */
    public function test_set_calledWithCurrency_setCurrencyNameInSession()
    {
        $this->session_double->set(WebCurrencyStrategy::CURRENCY_VAR, 'EUR')->shouldBeCalled();
        $sut = new WebCurrencyStrategy($this->session_double->reveal());
        $sut->set(new Currency('EUR'));
    }
}