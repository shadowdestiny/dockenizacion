<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 06/02/19
 * Time: 12:26 PM
 */

namespace EuroMillions\web\services\factories;

use EuroMillions\superenalotto\vo\SuperEnalottoPrize;
use EuroMillions\web\entities\Lottery;
use EuroMillions\shared\vo\LotteryPrize;
use EuroMillions\eurojackpot\vo\EuroJackpotPrize;
use EuroMillions\megasena\vo\MegaSenaPrize;

class LotteryPrizeFactory
{
    public static function create(Lottery $lottery, $breakDown, $result)
    {
        if ($lottery->isEuroJackpot()) {
            return new EuroJackpotPrize($breakDown, $result);
        }

        if ($lottery->isMegaSena()) {
            return new MegaSenaPrize($breakDown, $result);
        }

        if ($lottery->isSuperEnalotto()) {
            return new SuperEnalottoPrize($breakDown, $result);
        }

        return new LotteryPrize($breakDown, $result);
    }
}