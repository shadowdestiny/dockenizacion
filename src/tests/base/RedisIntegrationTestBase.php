<?php
namespace tests\base;

use Phalcon\Cache\Backend\Redis;
use Phalcon\DI;

abstract class RedisIntegrationTestBase extends \PHPUnit_Framework_TestCase
{

    const ENTITIES_NS = '\EuroMillions\entities\\';

    /** @var  Redis */
    protected $redis;

    protected function setUp()
    {
        parent::setUp();
        $this->redis = Di::getDefault()->get('redisCache');
        $this->redis->flush();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->redis->flush();
    }
}