<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 25/02/19
 * Time: 16:30
 */

namespace EuroMillions\superenalotto\vo;

use EuroMillions\shared\vo\LotteryPrize;

class SuperEnalottoPrize extends LotteryPrize
{
    public function mappingArray()
    {
        return [
            '3,0' => 'getCategoryFive',
            '4,0' => 'getCategoryFour',
            '5,0' => 'getCategoryThree',
            '5,1' => 'getCategoryTwo',
            '6,0' => 'getCategoryOne'
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