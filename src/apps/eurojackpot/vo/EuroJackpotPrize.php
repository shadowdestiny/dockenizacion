<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/07/18
 * Time: 11:30
 */

namespace EuroMillions\eurojackpot\vo;

use EuroMillions\shared\vo\LotteryPrize;

class EuroJackpotPrize extends LotteryPrize
{
    public function mappingArray()
    {
        return [
            '0,1' => 'getCategorySeventeen',
            '0,2' => 'getCategorySixteen',
            '1,0' => 'getCategoryFifteen',
            '1,1' => 'getCategoryFourteen',
            '2,0' => 'getCategoryThirteen',
            '2,1' => 'getCategoryTwelve',
            '1,2' => 'getCategoryEleven',
            '2,2' => 'getCategoryTen',
            '3,0' => 'getCategoryNine',
            '3,1' => 'getCategoryEight',
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