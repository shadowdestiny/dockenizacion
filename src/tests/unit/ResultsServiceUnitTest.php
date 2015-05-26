<?php
namespace tests\unit;

use EuroMillions\services\ResultsService;
use tests\base\EuromillionsDotIeRelatedTest;
use tests\base\UnitTestBase;

class ResultsServiceUnitTest extends UnitTestBase
{
    use EuromillionsDotIeRelatedTest;

    /** @var  ResultsService */
    protected $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new ResultsService();
    }

    /**
     * method updateResultsAndNextDraw
     * when calledWhenNextDrawIsNotInDatabase
     * should storeNextDraw
     */
    public function test_updateResultsAndNextDraw_calledWhenNextDrawIsNotInDatabase_storeNextDraw()
    {

        // la api debe retornar una entidad a persistir
        //debería llamar a persist del entity manager
    }

    /**
     * method updateResultsAndNextDraw
     * when called
     * should getLastResultFromApi
     */
    public function test_updateResultsAndNextDraw_called_getLastResultFromApi()
    {
        $this->markTestSkipped();
    }

    /**
     * method updateResultsAndNextDraw
     * when calledWhenResultIsAlreadyInDb
     * should doNothingIfResultIsTheSame
     */
    public function test_updateResultsAndNextDraw_calledWhenResultIsAlreadyInDb_doNothingIfResultIsTheSame()
    {
        $this->markTestSkipped();
    }

    /**
     * method updateResultsAndNextDraw
     * when calledWhenResultIsAlreadyInDb
     * should throwIfResultIsDifferent
     */
    public function test_updateResultsAndNextDraw_calledWhenResultIsAlreadyInDb_throwIfResultIsDifferent()
    {
        $this->markTestSkipped();
    }

    /**
     * method updateResultsAndNextDraw
     * when calledWhenResultIsNotInDb
     * should storeResultInDb
     */
    public function test_updateResultsAndNextDraw_calledWhenResultIsNotInDb_storeResultInDb()
    {
        $this->markTestSkipped();
    }
}