<?php


namespace EuroMillions\tests\integration;


use EuroMillions\web\services\play_strategies\RedisOrderStorageStrategy;
use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\vo\UserId;
use EuroMillions\tests\base\RedisIntegrationTestBase;
use EuroMillions\tests\helpers\mothers\OrderMother;

class RedisOrderStorageStrategyIntegrationTest extends RedisIntegrationTestBase
{

    /** @var  RedisPlayStorageStrategy */
    private $sut;

    private $userId;

    const EMLINE_FETCH_KEY = 'PlayStore_EMLINES';

    public function setUp()
    {
        parent::setUp();
        $this->userId = UserId::create();
        $this->sut = new RedisOrderStorageStrategy($this->redis);
    }


    /**
     * method save
     * when calledWithProperData
     * should storeCorrectlyOnStorage
     */
    public function test_save_calledWithProperData_storeCorrectlyOnStorage()
    {
        $order = OrderMother::aJustOrder()->build();
        $this->sut->save($order->toJsonData(), $this->userId);
        $expected = $order->toJsonData();
        $actual = $this->sut->findByKey($this->userId);
        $this->assertEquals($expected,$actual->getValues());
    }

}