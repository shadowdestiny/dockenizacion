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

    public static function anPowerBallLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(10),
                new EuroMillionsRegularNumber(26),
                new EuroMillionsRegularNumber(28),
                new EuroMillionsRegularNumber(59),
                new EuroMillionsRegularNumber(62),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(2),
            ]
        );
    }

    public static function anOtherPowerBallLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(26),
                new EuroMillionsRegularNumber(28),
                new EuroMillionsRegularNumber(30),
                new EuroMillionsRegularNumber(40),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(2),
            ]
        );
    }

    public static function anEuroJackpotLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(10),
                new EuroMillionsRegularNumber(26),
                new EuroMillionsRegularNumber(28),
                new EuroMillionsRegularNumber(30),
                new EuroMillionsRegularNumber(40),
            ],
            [
                new EuroMillionsLuckyNumber(1),
                new EuroMillionsLuckyNumber(4),
            ]
        );
    }

    public static function anotherEuroJackpotLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(10),
                new EuroMillionsRegularNumber(26),
                new EuroMillionsRegularNumber(28),
                new EuroMillionsRegularNumber(35),
                new EuroMillionsRegularNumber(46),
            ],
            [
                new EuroMillionsLuckyNumber(2),
                new EuroMillionsLuckyNumber(3),
            ]
        );
    }

    public static function aMegaSenaLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(1),
                new EuroMillionsRegularNumber(41),
                new EuroMillionsRegularNumber(44),
                new EuroMillionsRegularNumber(46),
                new EuroMillionsRegularNumber(54),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(58),
            ]
        );
    }

    public static function aSuperEnalottoLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(10),
                new EuroMillionsRegularNumber(20),
                new EuroMillionsRegularNumber(30),
                new EuroMillionsRegularNumber(40),
                new EuroMillionsRegularNumber(50),
            ],
            [
                new EuroMillionsLuckyNumber(70),
                new EuroMillionsLuckyNumber(60),
            ]
        );
    }

    public static function anotherSuperEnalottoLine()
    {
        return new EuroMillionsLine(
            [
                new EuroMillionsRegularNumber(10),
                new EuroMillionsRegularNumber(20),
                new EuroMillionsRegularNumber(30),
                new EuroMillionsRegularNumber(35),
                new EuroMillionsRegularNumber(55),
            ],
            [
                new EuroMillionsLuckyNumber(0),
                new EuroMillionsLuckyNumber(65),
            ]
        );
    }
}