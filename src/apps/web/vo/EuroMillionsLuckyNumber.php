<?php
namespace EuroMillions\web\vo;

class EuroMillionsLuckyNumber extends EuroMillionsResultNumber
{

    protected function getMinValue()
    {
        return 1;
    }

    protected function getMaxValue()
    {
        return 99;
    }
}