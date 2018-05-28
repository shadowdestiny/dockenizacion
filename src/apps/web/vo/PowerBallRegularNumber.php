<?php

namespace EuroMillions\web\vo;

class PowerBallRegularNumber extends PowerBallResultNumber
{
    protected function getMinValue()
    {
        return 1;
    }

    protected function getMaxValue()
    {
        return 69;
    }
}