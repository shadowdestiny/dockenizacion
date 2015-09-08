<?php


namespace tests\unit;


use EuroMillions\vo\ExpiryDate;
use Phalcon\Test\UnitTestCase;

class ExpiryDateUnitTest extends UnitTestCase
{
    /**
     * method __construct
     * when calledWithExpiredDate
     * should throw
     * @dataProvider getExpiryDates
     */
    public function test___construct_calledWithExpiredDate_throw($excepted)
    {
        $this->setExpectedException('\EuroMillions\exceptions\InvalidExpirationDateException');
        new ExpiryDate($excepted);
    }

    public function getExpiryDates()
    {
        return [
            ['11/10'],
            ['1110'],
            ['11 10'],
            ['08/15']
        ];
    }

}