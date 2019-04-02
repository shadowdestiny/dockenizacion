<?php

use EuroMillions\web\vo\cutoff\MegaSenaCutOff;
use EuroMillions\web\vo\cutoff\PowerBallCutOff;
use EuroMillions\web\vo\cutoff\EuroJackpotCutOff;
use EuroMillions\web\vo\cutoff\MegaMillionsCutOff;
use EuroMillions\web\vo\cutoff\EuroMillionsCutOff;

class CutOffCest
{
    /**
     * @var EuroMillions\web\components\EmTranslationAdapter
     */
    protected $emTranslationAdapter;

    public function _before(\UnitTester $I)
    {
    }

    /**
     * method isClosed
     * when megaSenaData
     * should getTheCorrectResult
     * @param UnitTester $I
     * @param Codeception\Example $data
     * @group cut-off
     * @dataProvider getMegaSenaData
     */
    public function test_isClosed_megaSenaData_getTheCorrectResult(UnitTester $I, \Codeception\Example $data)
    {
        $isTicketClosed = (new MegaSenaCutOff($data['draw_date']))->setDateToCompare($data['today_date'])->isClosed();
        $I->assertEquals($data['closed'], $isTicketClosed);
    }

    /**
     * method isClosed
     * when euroJackpotData
     * should getTheCorrectResult
     * @param UnitTester $I
     * @param Codeception\Example $data
     * @group cut-off
     * @dataProvider getEuroJackpotData
     */
    public function test_isClosed_euroJackpotData_getTheCorrectResult(UnitTester $I, \Codeception\Example $data)
    {
        $isTicketClosed = (new EuroJackpotCutOff($data['draw_date']))->setDateToCompare($data['today_date'])->isClosed();
        $I->assertEquals($data['closed'], $isTicketClosed);
    }

    /**
     * method isClosed
     * when megaMillionsData
     * should getTheCorrectResult
     * @param UnitTester $I
     * @param Codeception\Example $data
     * @group cut-off
     * @dataProvider getMegaMillionsData
     */
    public function test_isClosed_megaMillionsData_getTheCorrectResult(UnitTester $I, \Codeception\Example $data)
    {
        $isTicketClosed = (new MegaMillionsCutOff($data['draw_date']))->setDateToCompare($data['today_date'])->isClosed();
        $I->assertEquals($data['closed'], $isTicketClosed);
    }

    /**
     * method isClosed
     * when euroMillionsData
     * should getTheCorrectResult
     * @param UnitTester $I
     * @param Codeception\Example $data
     * @group cut-off
     * @dataProvider getEuroMillionsData
     */
    public function test_isClosed_euroMillionsData_getTheCorrectResult(UnitTester $I, \Codeception\Example $data)
    {
        $isTicketClosed = (new EuroMillionsCutOff($data['draw_date']))->setDateToCompare($data['today_date'])->isClosed();
        $I->assertEquals($data['closed'], $isTicketClosed);
    }

    /**
     * method isClosed
     * when powerBallData
     * should getTheCorrectResult
     * @param UnitTester $I
     * @param Codeception\Example $data
     * @group cut-off
     * @dataProvider getPowerBallData
     */
    public function test_isClosed_powerBallData_getTheCorrectResult(UnitTester $I, \Codeception\Example $data)
    {
        $isTicketClosed = (new PowerBallCutOff($data['draw_date']))->setDateToCompare($data['today_date'])->isClosed();
        $I->assertEquals($data['closed'], $isTicketClosed);
    }


    /**
     * @return array
     */
    protected function getMegaSenaData()
    {
        return [
            ['draw_date' => new \DateTime('2019-03-13 09:00:00'),    'today_date' => new \DateTime('2019-03-13 09:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-13 09:00:00'),    'today_date' => new \DateTime('2019-03-13 07:30:00'),  'closed' => false],
            ['draw_date' => new \DateTime('2019-03-16 16:00:00'),    'today_date' => new \DateTime('2019-03-16 17:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-16 16:00:00'),    'today_date' => new \DateTime('2019-03-16 14:30:00'),  'closed' => false],
        ];
    }

    /**
     * @return array
     */
    protected function getMegaMillionsData()
    {
        return [
            ['draw_date' => new \DateTime('2019-03-13 04:00:00'),    'today_date' => new \DateTime('2019-03-13 04:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-13 04:00:00'),    'today_date' => new \DateTime('2019-03-13 02:30:00'),  'closed' => false],
            ['draw_date' => new \DateTime('2019-03-16 04:00:00'),    'today_date' => new \DateTime('2019-03-16 04:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-16 04:00:00'),    'today_date' => new \DateTime('2019-03-16 02:30:00'),  'closed' => false],
        ];
    }

    /**
     * @return array
     */
    protected function getEuroMillionsData()
    {
        return [
            ['draw_date' => new \DateTime('2019-03-12 20:00:00'),    'today_date' => new \DateTime('2019-03-12 20:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-12 20:00:00'),    'today_date' => new \DateTime('2019-03-12 18:30:00'),  'closed' => false],
            ['draw_date' => new \DateTime('2019-03-15 20:00:00'),    'today_date' => new \DateTime('2019-03-15 20:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-15 20:00:00'),    'today_date' => new \DateTime('2019-03-15 18:30:00'),  'closed' => false],
        ];
    }

    /**
     * @return array
     */
    protected function getEuroJackpotData()
    {
        return [
            ['draw_date' => new \DateTime('2019-03-22 20:00:00'),    'today_date' => new \DateTime('2019-03-22 20:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-22 20:00:00'),    'today_date' => new \DateTime('2019-03-22 18:00:00'),  'closed' => false],
        ];
    }

    /**
     * @return array
     */
    protected function getPowerBallData()
    {
        return [
            ['draw_date' => new \DateTime('2019-03-14 04:30:00'),    'today_date' => new \DateTime('2019-03-14 04:30:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-14 04:30:00'),    'today_date' => new \DateTime('2019-03-14 02:00:00'),  'closed' => false],
            ['draw_date' => new \DateTime('2019-03-10 04:30:00'),    'today_date' => new \DateTime('2019-03-10 04:00:00'),  'closed' => true],
            ['draw_date' => new \DateTime('2019-03-10 04:30:00'),    'today_date' => new \DateTime('2019-03-10 02:00:00'),  'closed' => false],
        ];
    }
}
