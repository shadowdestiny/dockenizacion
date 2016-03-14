<?php
namespace EuroMillions\tests\base;

use Phalcon\DI;

abstract class RedisIntegrationTestBase extends \PHPUnit_Framework_TestCase
{

    const ENTITIES_NS = '\EuroMillions\entities\\';

    /** @var  \Redis */
    protected $redis;

    protected function setUp()
    {
        parent::setUp();
        $this->redis = Di::getDefault()->get('redisCache');
        $this->redis->flushAll();
    }
    protected function tearDown()
    {
        parent::tearDown();
        $this->redis->flushAll();
    }
}