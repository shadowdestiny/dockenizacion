<?php


namespace tests\unit;


use EuroMillions\web\vo\ExpiryDate;
use Phalcon\Test\UnitTestCase;

class ExpiryDateUnitTest extends UnitTestCase
{
    /**
     * method __construct
     * when calledWithBadExpiredDate
     * should throw
     * @dataProvider getBadExpiryDates
     */
    public function test___construct_calledWithExpiredDate_calledWithExpiredDate_throw($badExpiryDate)
    {
        $this->setExpectedException('\EuroMillions\web\exceptions\InvalidExpirationDateException', 'The expiration date is not valid.');
        new ExpiryDate($badExpiryDate);
    }

    public function getBadExpiryDates()
    {
        return [
            ['11/10'],
            ['1110'],
            ['11 10'],
            ['08/15'],
            ['1/2020'],
            ['13/2020'],
        ];
    }

    /**
     * method __construct
     * when calledWithExpiredDates
     * should throw
     * @dataProvider getCorrectButExpiredDates
     * @param $expiredDate
     */
    public function test___construct_calledWithExpiredDates_throw($expiredDate)
    {
        $this->setExpectedException('\EuroMillions\web\exceptions\InvalidExpirationDateException', 'The expiration date is not valid. Expired');
        new ExpiryDate($expiredDate, new \DateTime('2012/01/01'));
    }

    public function getCorrectButExpiredDates()
    {
        return [
            ['10/2010'],
            ['09/2010'],
            ['01/2009'],
            ['02/2010']
        ];
    }

    /**
     * method getMonthAndGetYear
     * when calledWithProperDate
     * should returnProperValue
     * @dataProvider getGoodExpiryDates
     */
    public function test_getMonthAndGetYear_calledWithProperDate_returnProperValue($date, $expectedMonth, $expectedYear)
    {
        $sut = new ExpiryDate($date, new \DateTime('2012/01/01'));
        $this->assertEquals($expectedMonth, $sut->getMonth());
        $this->assertEquals($expectedYear, $sut->getYear());
    }

    public function getGoodExpiryDates()
    {
        return [
            ['10/2016', '10', '2016'],
            ['02/2016', '02', '2016'],
            ['12/2016', '12', '2016'],
            ['12/2127', '12', '2127'],
        ];

    }

}