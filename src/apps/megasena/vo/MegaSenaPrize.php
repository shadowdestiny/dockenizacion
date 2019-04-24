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
            '3,1' => 'getCategoryThree',
            '4,0' => 'getCategoryThree',
            '4,1' => 'getCategoryTwo',
            '5,0' => 'getCategoryTwo',
            '5,1' => 'getCategoryOne',
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