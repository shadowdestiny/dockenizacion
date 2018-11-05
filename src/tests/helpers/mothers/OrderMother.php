<?php


namespace EuroMillions\tests\helpers\mothers;


use EuroMillions\tests\helpers\builders\OrderBuilder;

class OrderMother
{

    public static function aJustOrder()
    {
        return self::getInitializedOrder();
    }

    public static function aJustOrderWithSubscription()
    {
        return self::getInitializedOrder()
            ->withSubscription()
            ->withCheckedWalletBalance();
    }


    private static function getInitializedOrder()
    {
        return OrderBuilder::anOrder();
    }


}