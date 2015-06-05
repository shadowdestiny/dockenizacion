<?php
namespace tests\unit;

use EuroMillions\services\external_apis\LoteriasyapuestasDotEsApi;
use Phalcon\Http\Client\Provider\Curl;
use tests\base\LoteriasyapuestasDotEsRelatedTest;
use tests\base\UnitTestBase;

class LoteriasyapuestasDotEsApiUnitTest extends UnitTestBase
{
    use LoteriasyapuestasDotEsRelatedTest;
    /**
     * method getJackpotForDate
     * when calledWithNonAvailableDate
     * should throw
     */
    public function test_getJackpotForDate_calledWithNonAvailableDate_throw()
    {
        $this->setExpectedException('\EuroMillions\exceptions\ValidDateRangeException');
        $date = '2010-02-02';
        $this->exerciseGetJackpot($date);
    }

    /**
     * method getJackpotForDate
     * when calledWithAvailableDate
     * should returnProperValue
     * @dataProvider getDatesAndJackpots
     */
    public function test_getJackpotForDate_calledWithAvailableDate_returnProperValue($date, $expectedJackpot)
    {
        $actual = $this->exerciseGetJackpot($date);
        $this->assertEquals($expectedJackpot, $actual);
    }

    public function getDatesAndJackpots()
    {
        return [
            ['2015-06-05', 100000000],
            ['2015-06-02', 21000000],
            ['2015-05-29', 15000000],
            ['2015-05-26', 37000000],
            ['2015-05-22', 31000000],
        ];
    }

    /**
     * @param $date
     * @return int
     */
    protected function exerciseGetJackpot($date)
    {
        $curlWrapper_stub = $this->getCurlWrapperWithRssResponse();
        $sut = new LoteriasyapuestasDotEsApi();
        return $sut->getJackpotForDate('EuroMillions', $date, $curlWrapper_stub);
    }
}