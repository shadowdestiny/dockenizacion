<?php


namespace EuroMillions\components;

use EuroMillions\interfaces\IRedis;
use Phalcon\Cache\Backend\Redis;

class PhalconRedisWrapper extends Redis implements IRedis{

}