<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 12/11/18
 * Time: 10:54
 */

namespace EuroMillions\web\vo\enum;


class MoneyMatrixStatusCode
{

    private static $status = [
        1 => 'SUCCESS',
        2 => 'ERROR',
        3 => 'PENDING_NOTIFICATION',
        4 => 'PENDING_CONFIRMATION',
        5 => 'REJECTED',
        6 => 'CANCELED',
        7 => 'VOIDED',
        8 => 'PENDING_APPROVAL',
        9 => 'AUTHORIZED'
    ];


    public function getValue($key)
    {
        return self::$status[$key];
    }

}