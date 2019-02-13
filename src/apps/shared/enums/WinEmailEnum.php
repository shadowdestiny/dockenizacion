<?php


namespace EuroMillions\shared\enums;

class WinEmailEnum extends \SplEnum
{
    const EuroMillions = 'web';

    const PowerBall = 'web';

    const MegaMillions = 'megamillions';

    const EuroJackpot = 'eurojackpot';

    public function findTemplatePathByLotteryName($lotteryName, $isBig = false)
    {
        $declaredElems = $this->getConstList();
        $above = $isBig ? 'Above' : '';

        if (array_key_exists($lotteryName, $declaredElems)) {
            if(strtolower($lotteryName) == self::EuroJackpot || strtolower($lotteryName) == self::MegaMillions ) {
                return 'EuroMillions\\'.strtolower($lotteryName).'\emailTemplates\WinEmail'.$lotteryName.$above.'Template';
            }
            return $lotteryName == 'PowerBall' ?
                "EuroMillions\\web\\emailTemplates\\WinEmailPowerBall".$above.'Template':
                "EuroMillions\\web\\emailTemplates\\WinEmail".$above.'Template';
        }

        throw new \UnexpectedValueException('Lottery unknown');
    }
}