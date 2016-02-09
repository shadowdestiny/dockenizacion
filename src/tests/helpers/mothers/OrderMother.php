<?php


namespace tests\helpers\mothers;


use tests\helpers\builders\OrderBuilder;

class OrderMother
{

    public static function aJustOrder()
    {
        return self::getInitializedOrder();
    }


    private static function getInitializedOrder()
    {
        $order = OrderBuilder::anOrder();
        return $order;
    }


}