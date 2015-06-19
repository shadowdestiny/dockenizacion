<?php
namespace tests\unit;

use EuroMillions\services\external_apis\LoteriasyapuestasDotEsApi;
use Money\Currency;
use Money\Money;
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
     * @param string $date
     * @param int $expectedJackpot
     */
    public function test_getJackpotForDate_calledWithAvailableDate_returnProperValue($date, $expectedJackpot)
    {
        $actual = $this->exerciseGetJackpot($date);
        $this->assertEquals(new Money($expectedJackpot, new Currency('EUR')), $actual);
    }

    public function getDatesAndJackpots()
    {
        return [
            ['2015-06-05', 10000000000],
            ['2015-06-02', 2100000000],
            ['2015-05-29', 1500000000],
            ['2015-05-26', 3700000000],
            ['2015-05-22', 3100000000],
        ];
    }

    /**
     * @param $date
     * @return int
     */
    protected function exerciseGetJackpot($date)
    {
        $curlWrapper_stub = $this->getCurlWrapperWithJackpotRssResponse();
        $sut = new LoteriasyapuestasDotEsApi($curlWrapper_stub);
        return $sut->getJackpotForDate('EuroMillions', $date);
    }

    /**
     * method getResultForDate
     * when calledWithNonAvailableDate
     * should throw
     */
    public function test_getResultForDate_calledWithNonAvailableDate_throw()
    {
        $this->setExpectedException('\EuroMillions\exceptions\ValidDateRangeException');
        $date = '2010-02-02';
        $this->exerciseGetResult($date);
    }

    /**
     * method getResultForDate
     * when calledWithAvailableDate
     * should returnProperValues
     * @dataProvider getDatesAndResults
     */
    public function test_getResultForDate_calledWithAvailableDate_returnProperValues($date, $expected)
    {
        $actual = $this->exerciseGetResult($date);
        $this->assertEquals($expected, $actual);
    }

    public function getDatesAndResults()
    {
        return [
            ['2015-06-05',
                [
                    'regular_numbers' => ['02', '07', '08', '45', '48'],
                    'lucky_numbers'   => ['01', '09'],
                ]
            ],
            ['2015-06-02',
                [
                    'regular_numbers' => ['07', '23', '29', '37', '41'],
                    'lucky_numbers'   => ['01', '08'],
                ]
            ],
            ['2015-05-29',
                [
                    'regular_numbers' => ['03', '04', '20', '45', '48'],
                    'lucky_numbers'   => ['06', '08'],
                ]
            ],
            ['2015-05-26',
                [
                    'regular_numbers' => ['05', '06', '07', '21', '24'],
                    'lucky_numbers'   => ['05', '06'],
                ]
            ],
            ['2015-05-22',
                [
                    'regular_numbers' => ['18', '24', '35', '44', '45'],
                    'lucky_numbers'   => ['05', '11'],
                ]
            ],
        ];
    }


    protected function exerciseGetResult($date)
    {
        $curlWrapper_stub = $this->getCurlWrapperWithResultRssResponse();
        $sut = new LoteriasyapuestasDotEsApi($curlWrapper_stub);
        return $sut->getResultForDate('EuroMillions', $date);
    }
}