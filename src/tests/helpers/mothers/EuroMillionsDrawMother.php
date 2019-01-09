<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\EuroMillionsDrawBuilder;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\PowerBallDrawBreakDown;
use Money\Currency;
use Money\Money;

class EuroMillionsDrawMother
{


    private static $powerBallJsonResult='{
        "currency": "USD",
        "date": "2018-11-07",
        "jackpot": {
            "rollover": 2,
            "total": "71000000.00"
        },
        "numbers": {
            "main": [
                26,
                28,
                34,
                42,
                50
            ],
            "powerball": 25,
            "powerplay": 2
        },
        "prizes": {
            "match-0-p": "4.00",
            "match-0-p-pp": "8.00",
            "match-1-p": "4.00",
            "match-1-p-pp": "8.00",
            "match-2-p": "7.00",
            "match-2-p-pp": "14.00",
            "match-3": "7.00",
            "match-3-p": "100.00",
            "match-3-p-pp": "200.00",
            "match-3-pp": "14.00",
            "match-4": "100.00",
            "match-4-p": "50000.00",
            "match-4-p-pp": "100000.00",
            "match-4-pp": "200.00",
            "match-5": "1000000.00",
            "match-5-p": "71000000.00",
            "match-5-pp": "2000000.00"
        },
        "type": "powerball",
        "winners": {
            "match-0-p": 254996,
            "match-0-p-pp": 70489,
            "match-1-p": 101929,
            "match-1-p-pp": 27249,
            "match-2-p": 12905,
            "match-2-p-pp": 3463,
            "match-3": 16248,
            "match-3-p": 585,
            "match-3-p-pp": 158,
            "match-3-pp": 4390,
            "match-4": 248,
            "match-4-p": 15,
            "match-4-p-pp": 2,
            "match-4-pp": 70,
            "match-5": 0,
            "match-5-p": 0,
            "match-5-pp": 1
            }
    }';
    /**
     * @return EuroMillionsDrawBuilder
     */
    public static function anEuroMillionsDrawWithJackpotAndBreakDown()
    {
        $jackpot = new Money(3000000000, new Currency('EUR'));
        $breakDown = new EuroMillionsDrawBreakDown([
            'category_one' => ['5 + 2', new Money(3000000000, new Currency('EUR')), '1'],
            'category_two' => ['5 + 1', new Money(29367800, new Currency('EUR')), '9'],
            'category_three' => ['5 + 0', new Money(8817700, new Currency('EUR')), '10'],
            'category_four' => ['4 + 2', new Money(668000, new Currency('EUR')), '66'],
            'category_five' => ['4 + 1', new Money(27500, new Currency('EUR')), '1.402'],
            'category_six' => ['4 + 0', new Money(13100, new Currency('EUR')), '2.934'],
            'category_seven' => ['3 + 2', new Money(6000, new Currency('EUR')), '4.527'],
            'category_eight' => ['2 + 2', new Money(1800, new Currency('EUR')), '66.973'],
            'category_nine' => ['3 + 1', new Money(1600, new Currency('EUR')), '72.488'],
            'category_ten' => ['3 + 0', new Money(1300, new Currency('EUR')), '152.009'],
            'category_eleven' => ['1 + 2', new Money(900, new Currency('EUR')), '358.960'],
            'category_twelve' => ['2 + 1', new Money(852, new Currency('EUR')), '1.138.617'],
            'category_thirteen' => ['2 + 0', new Money(415, new Currency('EUR')), '2.390.942'],
        ]);
        $line = EuroMillionsLineMother::anEuroMillionsLine();
        return EuroMillionsDrawBuilder::aDraw()->withJackpot($jackpot)->withBreakDown($breakDown)->withResult($line);
    }

    public static function anPowerBallDrawWithJackpotAndBreakDown()
    {
        $jackpot = new Money(5000000000, new Currency('EUR'));
        $breakDown =
            new PowerBallDrawBreakDown(json_decode(self::$powerBallJsonResult, TRUE));
        $line = EuroMillionsLineMother::anPowerBallLine();
        return EuroMillionsDrawBuilder::aDraw()
            ->withLottery(LotteryMother::aPowerBall())
            ->withDrawDate(new \DateTime('2020-01-09'))
            ->withJackpot($jackpot)->withBreakDown($breakDown)->withResult($line);
    }

}

