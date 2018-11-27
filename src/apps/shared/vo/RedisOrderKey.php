<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 21/11/18
 * Time: 9:54
 */

namespace EuroMillions\shared\vo;


class RedisOrderKey
{

    private $key;

    private function __construct($userId, $lotteryId)
    {
        $this->key = $userId.$lotteryId;
    }

    public static function create($userId, $lotteryId)
    {
        return new static($userId, $lotteryId);
    }

    public function key()
    {
        return $this->key;
    }

}
