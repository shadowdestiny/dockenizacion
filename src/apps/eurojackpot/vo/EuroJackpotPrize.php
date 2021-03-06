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
            '2,1' => 'getCategoryTwelve',
            '1,2' => 'getCategoryEleven',
            '3,0' => 'getCategoryNine',
            '3,1' => 'getCategoryEight',
            '2,2' => 'getCategoryTen',
            '3,2' => 'getCategorySeven',
            '4,0' => 'getCategorySix',
            '4,1' => 'getCategoryFive',
            '4,2' => 'getCategoryFour',
            '5,0' => 'getCategoryThree',
            '5,1' => 'getCategoryTwo',
            '5,2' => 'getCategoryOne'
        ];
    }

    /**
     * @return mixed
     */
    public function getPrize()
    {
        return $this->prize->multiply(100);
    }
}