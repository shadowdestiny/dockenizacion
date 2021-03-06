<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/07/18
 * Time: 11:30
 */

namespace EuroMillions\shared\vo;


class LotteryPrize extends Prize
{


    public function __construct($breakDown, array $categoryCombination)
    {
        parent::__construct($breakDown, $categoryCombination);
    }

    protected function setPrize()
    {
        $countCombination = implode(',',$this->categoryCombination);

        $mappingArray = $this->mappingArray();

        $this->prize = $this->breakDown->$mappingArray[$countCombination]()->getLotteryPrize();
    }

    public function mappingArray()
    {
        return [
            '0,1,0' => 'getCategorySeventeen',
            '0,1,1' => 'getCategorySixteen',
            '1,1,0' => 'getCategoryFifteen',
            '1,1,1' => 'getCategoryFourteen',
            '2,1,0' => 'getCategoryThirteen',
            '2,1,1' => 'getCategoryTwelve',
            '3,0,0' => 'getCategoryEleven',
            '3,1,0' => 'getCategoryTen',
            '3,1,1' => 'getCategoryNine',
            '3,0,1' => 'getCategoryEight',
            '4,0,0' => 'getCategorySeven',
            '4,1,0' => 'getCategorySix',
            '4,1,1' => 'getCategoryFive',
            '4,0,1' => 'getCategoryFour',
            '5,0,0' => 'getCategoryThree',
            '5,1,0' => 'getCategoryTwo',
            '5,0,1' => 'getCategoryOne'
        ];
    }

}