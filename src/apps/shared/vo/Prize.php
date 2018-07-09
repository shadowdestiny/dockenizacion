<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 9/07/18
 * Time: 11:29
 */

namespace EuroMillions\shared\vo;


class Prize
{


    protected $breakDown;

    protected $categoryCombination;


    public function __construct($breakDown, array $categoryCombination)
    {
        $this->breakDown = $breakDown;
        $this->categoryCombination = $categoryCombination;
    }

}