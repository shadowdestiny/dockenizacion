<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\OrderBuilder;

class OrderMother
{

    public static function aJustOrder()
    {
        return self::getInitializedOrder();
    }


    private static function getInitializedOrder()
    {
        return OrderBuilder::anOrder();
    }


}