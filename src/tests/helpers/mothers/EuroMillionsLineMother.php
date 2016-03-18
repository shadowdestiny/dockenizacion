<?php
namespace EuroMillions\tests\helpers\mothers;

use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;

class EuroMillionsLineMother
{
    public static function anEuroMillionsLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(2),
                new EuroMillionsRegularNumber(3),
                new EuroMillionsRegularNumber(4),
                new EuroMillionsRegularNumber(5),
            ],
            [
                new EuroMillionsLuckyNumber(1),
                new EuroMillionsLuckyNumber(2),
            ]
        );
    }

}