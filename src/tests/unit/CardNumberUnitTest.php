<?php


namespace tests\unit;


use EuroMillions\vo\CardNumber;
use Phalcon\Test\UnitTestCase;

class CardNumberUnitTest extends UnitTestCase
{

    /**
     * method __construct
     * when calledWithWrongCardNumber
     * should throw
     * @dataProvider getCardNumbers
     * @param $expected
     */
    public function test___construct_calledWithWrongCardNumber_throw($expected)
    {
        $this->setExpectedException('EuroMillions\exceptions\InvalidCardNumberException');
        new CardNumber($expected);
    }

    public function getCardNumbers()
    {
        return [
            ['41101441fr10144115'],
            ['fdsfds4343'],
            [''],
            [null],
            [' '],
        ];

    }

    /**
     * method type
     * when called
     * should returnPaymentMethodVisaName
     */
    public function test_type_called_returnPaymentMethodVisaName()
    {
        $actual = (new CardNumber('4110144110144115'))->type();
        $this->assertEquals('Visa',$actual);
    }

    /**
     * method type
     * when called
     * should returnProperCompanyName
     * @dataProvider getNumbersAndNames
     * @param $number
     * @param $expected
     */
    public function test_type_called_returnProperCompanyName($number, $expected)
    {
        $sut = new CardNumber($number);
        $actual = $sut->type();
        $this->assertEquals($expected,$actual, "Failed asserting company for number: $number");
    }

    public function getNumbersAndNames()
    {
        return [
            ['4444444444444448', 'Visa'],
            ['5500005555555559', 'Mastercard'],
            ['371449635398431', 'American Express'],
            ['4444 4444 4444 4448', 'Visa'],
            ['5500 0055 5555 5559', 'Mastercard'],
//            ['371449635398431', 'American Express'],
            ['4444-4444-4444-4448', 'Visa'],
            ['5500-0055-5555-5559', 'Mastercard'],
//            ['371449635398431', 'American Express'],
        ];
    }



}