<?php


namespace EuroMillions\shared\enums;


use http\Exception\UnexpectedValueException;

class PurchaseConfirmationEnum extends \SplEnum
{

    const EuroMillions = 'web';

    const MegaMillions = 'megamillions';

    const PowerBall = 'web';

    const EuroJackpot = 'eurojackpot';


    public function findTemplatePathByLotteryName($lotteryName)
    {
        $declaredElems = $this->getConstList();
        if(array_key_exists($lotteryName, $declaredElems)) {
            if(strtolower($lotteryName) == self::EuroJackpot || strtolower($lotteryName)== self::MegaMillions)
            {
                return "EuroMillions\\".strtolower($lotteryName)."\\emailTemplates\\".$lotteryName."PurchaseConfirmationEmailTemplate";
            }
            return "EuroMillions\\web\\emailTemplates\\".$lotteryName."PurchaseConfirmationEmailTemplate";
        }
        throw new \UnexpectedValueException('Lottery unknown');
    }


}