<?php

namespace EuroMillions\vo;

class EuroMillionsRegularNumber extends EuroMillionsResultNumber
{
    protected function getMinValue()
    {
        return 1;
    }

    protected function getMaxValue()
    {
        return 50;
    }
}