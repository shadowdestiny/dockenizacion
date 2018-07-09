<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/07/18
 * Time: 11:29
 */

namespace EuroMillions\shared\vo;


use EuroMillions\web\vo\EuroMillionsDrawBreakDown;

class Prize
{


    protected $breakDown;

    protected $categoryCombination;

    protected $prize;

    public function __construct(EuroMillionsDrawBreakDown $breakDown, array $categoryCombination)
    {
        $this->breakDown = $breakDown;
        $this->categoryCombination = $categoryCombination;
        $this->setPrize();
    }

    /**
     * @return mixed
     */
    public function getPrize()
    {
        return $this->prize;
    }



    protected function setPrize()
    {

    }

}