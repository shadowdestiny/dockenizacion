<?php


namespace tests\unit;


use EuroMillions\vo\ExpiryDate;
use Phalcon\Test\UnitTestCase;

class ExpiryDateUnitTest extends UnitTestCase
{
    /**
     * method assertExpiryDate
     * when calledWithExpiredDate
     * should throw
     * @dataProvider getExpiryDates
     */
    public function test___calledWithExpiredDate_calledWithExpiredDate_throw($excepted)
    {
        $this->setExpectedException('\EuroMillions\exceptions\InvalidExpirationDateException');
        $sut = new ExpiryDate();
        $sut->assertExpiryDate($excepted);


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