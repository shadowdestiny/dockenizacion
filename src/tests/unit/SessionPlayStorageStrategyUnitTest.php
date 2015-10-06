<?php


namespace tests\unit;

use EuroMillions\services\play_strategies\SessionPlayStorageStrategy;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\LastDrawDate;
use EuroMillions\vo\PlayFormToStorage;
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
        $expected = new ServiceActionResult(true,$this->getEuroMillionsLines());
        $sut = $this->getSut();
        $this->session_double->get(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR)->willReturn($this->getEuroMillionsLines());
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
        $playFormToStorage = $this->getPlayFormToStorage();
        $this->session_double->set(SessionPlayStorageStrategy::CURRENT_EMLINE_VAR, $playFormToStorage->toJson())->shouldBeCalled();
        $this->exerciseSaveAll($playFormToStorage);
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