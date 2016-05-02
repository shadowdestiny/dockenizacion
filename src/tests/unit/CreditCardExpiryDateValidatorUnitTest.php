<?php
namespace EuroMillions\tests\unit;

use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\forms\validators\CreditCardExpiryDateValidator;
use Phalcon\Validation\Message;

class CreditCardExpiryDateValidatorUnitTest extends UnitTestBase
{
    private $validation_double;

    public function setUp()
    {
        parent::setUp();
        $this->validation_double = $this->prophesize('Phalcon\Validation');
    }

    /**
     * method validate
     * when calledWithABadMonth
     * should setErrorMessageAndReturnFalse
     * @dataProvider getBadMonths
     */
    public function test_validate_calledWithABadMonth_setErrorMessageAndReturnFalse($month)
    {
        $attribute = 'expiry-date-month';
        $this->validation_double->getValue($attribute)->willReturn($month);
        $this->validation_double->getValue('expiry-date-year')->willReturn(null);
        $this->validation_double->appendMessage(new Message('The month is not valid'))->shouldBeCalled();
        $sut = new CreditCardExpiryDateValidator(['what' => 'month', 'with'=> 'expiry-date-year']);
        $actual = $sut->validate($this->validation_double->reveal(), $attribute);
        self::assertFalse($actual);
    }

    public function getBadMonths()
    {
        return [
            ['13'],
            ['null'],
            [null],
            ['0'],
            [0],
            ['083'],
        ];
    }

    /**
     * method validate
     * when calledWithABadYear
     * should setErrorMessageAndReturnFalse
     * @dataProvider getBadYears
     */
    public function test_validate_calledWithABadYear_setErrorMessageAndReturnFalse($year)
    {
        $attribute = 'expiry-date-year';
        $this->validation_double->getValue($attribute)->willReturn($year);
        $this->validation_double->getValue('expiry-date-month')->willReturn(null);
        $this->validation_double->appendMessage(new Message('The year is not valid'))->shouldBeCalled();
        $sut = new CreditCardExpiryDateValidator(['what' => 'year', 'with'=> 'expiry-date-month']);
        $actual = $sut->validate($this->validation_double->reveal(), $attribute);
        self::assertFalse($actual);
    }

    public function getBadYears()
    {
        return [
            ['bad'],
            [null],
            ['234'],
        ];
    }

    /**
     * method validate
     * when calledWithCorrectMonthAndYearButExpiredDate
     * should setErrorMessageAndReturnFalse
     * @dataProvider getCorrectButExpiredDates
     */
    public function test_validate_calledWithCorrectMonthAndYearButExpiredDate_setErrorMessageAndReturnFalse($month, $year, $what, $with)
    {
        $today = new \DateTime('2016-04-20');
        $this->validation_double->getValue('expiry-date-month')->willReturn($month);
        $this->validation_double->getValue('expiry-date-year')->willReturn($year);
        $this->validation_double->appendMessage(new Message('The card is expired.'))->shouldBeCalled();
        $sut = new CreditCardExpiryDateValidator(['what' => $what, 'with' => $with]);
        $actual = $sut->validate($this->validation_double->reveal(), 'expiry-date-'.$what, $today);
        self::assertFalse($actual);
    }

    public function getCorrectButExpiredDates()
    {
        return [
            ['01', '16', 'month', 'expiry-date-year'],
            ['01', '16', 'year', 'expiry-date-month'],
            ['10', '15', 'month', 'expiry-date-year'],
            ['10', '15', 'year', 'expiry-date-month'],
        ];
    }

    /**
     * method validate
     * when calledWithCorrectMonthAndYear
     * should returnTrue
     * @dataProvider getCorrectExpiryDates
     */
    public function test_validate_calledWithCorrectMonthAndYear_returnTrue($month, $year, $what, $with)
    {
        $today = new \DateTime('2016-04-20');
        $this->validation_double->getValue('expiry-date-month')->willReturn($month);
        $this->validation_double->getValue('expiry-date-year')->willReturn($year);
        $sut = new CreditCardExpiryDateValidator(['what' => $what, 'with' => $with]);
        $actual = $sut->validate($this->validation_double->reveal(), 'expiry-date-'.$what, $today);
        self::assertTrue($actual);

    }

    public function getCorrectExpiryDates()
    {
        return [
            ['04','17','month','expiry-date-year'],
            ['04','17','year','expiry-date-month'],
            ['01','19','month','expiry-date-year'],
            ['01','19','year','expiry-date-month'],
            ['10','24','year','expiry-date-month'],
            ['10','24','month','expiry-date-year'],
        ];
    }
}