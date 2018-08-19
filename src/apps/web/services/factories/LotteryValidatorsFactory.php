<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/08/18
 * Time: 15:11
 */

namespace EuroMillions\web\services\factories;


use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\web\services\external_apis\LottorisqApi;

class LotteryValidatorsFactory
{

    public static function create($lotteryName)
    {
        if($lotteryName == 'EuroMillions')
        {
            return new LotteryValidationCastilloApi();
        }
        if($lotteryName == 'PowerBall')
        {
            return new LottorisqApi();
        }
    }
}