<?php


namespace EuroMillions\sharecomponents;

use EuroMillions\web\interfaces\IRedis;
use Phalcon\Cache\Backend\Redis;

class PhalconRedisWrapper extends Redis implements IRedis{

}