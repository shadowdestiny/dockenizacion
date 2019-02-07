<?php


namespace EuroMillions\shared\enums;


use http\Exception\UnexpectedValueException;

class WinEmailEnum extends \SplEnum
{

    public function findTemplatePathByLotteryName($lotteryName, $isBig = false)
    {
        if ($isBig) {
            if ($lotteryName == 'EuroJackpot') {
                return 'EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotAboveTemplate';
            }

            return 'EuroMillions\web\emailTemplates\WinEmailPowerBallAboveTemplate';
        }

        if ($lotteryName == 'MegaMillions' || $lotteryName == 'EuroJackpot') {
            return 'EuroMillions\\'.strtolower ($lotteryName).'\emailTemplates\WinEmail'.$lotteryName.'Template';
        }

        return 'EuroMillions\web\emailTemplates\WinEmailPowerBallTemplate';;
    }
}