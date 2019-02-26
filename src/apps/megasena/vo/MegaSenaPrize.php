<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 25/02/19
 * Time: 16:30
 */

namespace EuroMillions\megasena\vo;

use EuroMillions\shared\vo\LotteryPrize;

class MegaSenaPrize extends LotteryPrize
{
    public function mappingArray()
    {
        return [
            '2,1' => 'getCategoryTwelve',
            '1,2' => 'getCategoryEleven',
            '3,0' => 'getCategoryTen',
            '3,1' => 'getCategoryNine',
            '2,2' => 'getCategoryEight',
            '3,2' => 'getCategorySeven',
            '4,0' => 'getCategorySix',
            '4,1' => 'getCategoryFive',
            '4,2' => 'getCategoryFour',
            '5,0' => 'getCategoryThree',
            '5,1' => 'getCategoryTwo',
            '5,2' => 'getCategoryOne'
        ];
    }
}