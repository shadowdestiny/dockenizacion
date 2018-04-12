<?php

namespace EuroMillions\web\vo;

class PowerBallLuckyNumber extends PowerBallResultNumber
{

    protected function getMinValue()
    {
        return 1;
    }

    protected function getMaxValue()
    {
        return 25;
    }
}