<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\EuroMillionsDrawBuilder;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Currency;
use Money\Money;

class EuroMillionsDrawMother
{
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
}

