<?php


namespace EuroMillions\shared\enums;


use http\Exception\UnexpectedValueException;

class PurchaseConfirmationEnum extends \SplEnum
{

    const EuroMillions = 'web';

    const MegaMillions = 'megamillions';

    const PowerBall = 'web';

    const EuroJackpot = 'eurojackpot';

    const MegaSena = 'megasena';

    const Christmas = 'web';

    public function findTemplatePathByLotteryName($lotteryName,$isSubscription=false)
    {
        $declaredElems = $this->getConstList();
        $template = $isSubscription ? 'PurchaseSubscriptionConfirmationEmailTemplate' : 'PurchaseConfirmationEmailTemplate';
        if(array_key_exists($lotteryName, $declaredElems)) {
            if(strtolower($lotteryName) == self::EuroJackpot || strtolower($lotteryName)== self::MegaMillions || strtolower($lotteryName)== self::MegaSena)
            {
                return "EuroMillions\\".strtolower($lotteryName)."\\emailTemplates\\".$lotteryName.$template;
            }

            if(strtolower($lotteryName) == "euromillions") {
                return "EuroMillions\\web\\emailTemplates\\".$template;
            }

            if(strtolower($lotteryName) == "christmas") { //no subscription case.
                return "EuroMillions\\web\\emailTemplates\\PurchaseConfirmationChristmasEmailTemplate";
            }

            return "EuroMillions\\web\\emailTemplates\\".$lotteryName.$template;
        }
        throw new \UnexpectedValueException('Lottery unknown');
    }


}