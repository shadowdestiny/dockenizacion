<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\LoteriasyapuestasDotEsRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

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
        $this->setExpectedException($this->getExceptionToArgument('ValidDateRangeException'));
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
        $this->setExpectedException($this->getExceptionToArgument('ValidDateRangeException'));
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

    /**
     * method getResultBreakDownForDate
     * when calledWithAvailableDate
     * should returnProperValues
     * @dataProvider getDateAndBreakDownResults
     */
    public function test_getResultBreakDownForDate_calledWithAvailableDate_returnProperValues($date, $expected)
    {
        $actual = $this->exerciseGetResultBreakDown($date);
        $this->assertEquals($expected, $actual);
    }

    public function getDateAndBreakDownResults()
    {
        return [
            ['2015-06-05',
                [
                    'category_one' => ['5 + 2', new Money(str_replace('.','','0.00')*100, new Currency('EUR')), '0'],
                    'category_two' => ['5 + 1', new Money(str_replace('.','','293.926.57')*100, new Currency('EUR')), '9'],
                    'category_three' => ['5 + 0', new Money(str_replace('.','','88.177.97')*100, new Currency('EUR')), '10'],
                    'category_four' => ['4 + 2', new Money(str_replace('.','','6.680.15')*100, new Currency('EUR')), '66'],
                    'category_five' => ['4 + 1', new Money(str_replace('.','','275.16')*100, new Currency('EUR')), '1.402'],
                    'category_six' => ['4 + 0', new Money(str_replace('.','','131.49')*100, new Currency('EUR')), '2.934'],
                    'category_seven' => ['3 + 2', new Money(str_replace('.','','60.87')*100, new Currency('EUR')), '4.527'],
                    'category_eight' => ['2 + 2', new Money(str_replace('.','','18.93')*100, new Currency('EUR')), '66.973'],
                    'category_nine' => ['3 + 1', new Money(str_replace('.','','16.73')*100, new Currency('EUR')), '72.488'],
                    'category_ten' => ['3 + 0', new Money(str_replace('.','','13.41')*100, new Currency('EUR')), '152.009'],
                    'category_eleven' => ['1 + 2', new Money(str_replace('.','','9.98')*100, new Currency('EUR')), '358.960'],
                    'category_twelve' => ['2 + 1', new Money(str_replace('.','','8.52')*100, new Currency('EUR')), '1.138.617'],
                    'category_thirteen' => ['2 + 0', new Money(str_replace('.','','4.15')*100, new Currency('EUR')), '2.390.942'],
                ]
            ],
        ];
    }

    protected function exerciseGetResultBreakDown($date)
    {
        $curlWrapper_stub = $this->getCurlWrapperWithResultRssResponse();
        $sut = new LoteriasyapuestasDotEsApi($curlWrapper_stub);
        return $sut->getResultBreakDownForDate('EuroMillions', $date);
    }

    protected function exerciseGetResult($date)
    {
        $curlWrapper_stub = $this->getCurlWrapperWithResultRssResponse();
        $sut = new LoteriasyapuestasDotEsApi($curlWrapper_stub);
        return $sut->getResultForDate('EuroMillions', $date);
    }
}