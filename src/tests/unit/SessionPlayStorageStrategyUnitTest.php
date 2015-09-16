<?php


namespace tests\unit;

use EuroMillions\services\play_strategies\SessionPlayStorageStrategy;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\ServiceActionResult;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class SessionPlayStorageStrategyUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $session_double;

    public function setUp()
    {
        parent::setUp();
        $this->session_double = $this->getInterfaceDouble('ISession');
    }

    /**
     * method findByKey
     * when calledWithEuroMillionsLineInSession
     * should returnEuroMillionInSession
     */
    public function test_findByKey_calledWithEuroMillionsLineInSession_returnEuroMillionInSession()
    {
        $expected = new ServiceActionResult(true,$this->getEuroMillionsLine());
        $sut = $this->getSut();
        $this->session_double->get(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR)->willReturn($this->getEuroMillionsLine());
        $actual = $sut->findByKey(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method findByKey
     * when calledWithoutEuroMillionsLineInSession
     * should returnServiceActionResultFalse
     */
    public function test_findByKey_calledWithoutEuroMillionsLineInSession_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false,'No EuroMillions lines in session');
        $sut = $this->getSut();
        $this->session_double->get(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR)->willReturn(false);
        $actual = $sut->findByKey(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method findByKey
     * when calledWithAEmptyKey
     * should returnServiceActionResultFalse
     */
    public function test_findByKey_calledWithAKeyInvalid_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false, 'Key is invalid in session');
        $sut = $this->getSut();
        $actual = $sut->findByKey(null);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method saveAll
     * when calledWithProperArrayEuroMillionsLine
     * should setEuroMillionsLineSession
     */
    public function test_saveAll_calledWithProperArrayEuroMillionsLine_setEuroMillionsLineSession()
    {
        $euroMillionsLine = $this->getEuroMillionsLine();
        $this->session_double->set(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR, $euroMillionsLine)->shouldBeCalled();
        $this->exerciseSaveAll($euroMillionsLine);
    }

    /**
     * method remove
     * when called
     * should removeEuroMillionsLineArrayFromSession
     */
    public function test_remove_called_removeEuroMillionsLineArrayFromSession()
    {
        $sut = $this->getSut();
        $this->session_double->destroy()->shouldBeCalled();
        $sut->delete();
    }


    private function getEuroMillionsLine()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];

        $r_numbers = $this->getRegularNumbers($regular_numbers);
        $l_numbers = $this->getLuckyNumbers($lucky_numbers);

        $euroMillionsLine = [
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers)
        ];
        return $euroMillionsLine;
    }

    /**
     * @return SessionPlayStorageStrategy
     */
    protected function getSut()
    {
        $sut = new SessionPlayStorageStrategy($this->session_double->reveal());
        return $sut;
    }


    private function exerciseSaveAll($expected)
    {
        $sut = $this->getSut();
        $sut->saveAll($expected);
    }



}