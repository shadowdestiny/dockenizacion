<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\play_strategies\RedisOrderStorageStrategy;
use EuroMillions\web\vo\UserId;
use RedisException;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\OrderMother;

class RedisOrderStorageStrategyUnitTest extends UnitTestBase
{

    private $redis_double;

    private $authService_double;

    private $userId;

    const EMLINE_FETCH_KEY = 'PlayStore_EMORDER:';

    public function setUp()
    {
        $this->redis_double = $this->prophesize('\Redis');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->userId = UserId::create();
        parent::setUp();
    }

    /**
     * method save
     * when calledPassingProperData
     * should persistOrderOnStorage
     */
    public function test_save_calledPassingProperData_persistOrderOnStorage()
    {

        $expected = new ActionResult(true);
        $order = OrderMother::aJustOrder()->build();
        $sut = $this->getSut();
        $this->redis_double->set(self::EMLINE_FETCH_KEY.$this->userId, $order->toJsonData())->shouldBeCalled();
        $actual = $sut->save($order->toJsonData(),$this->userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method save
     * when calledWithkeyIsUnable
     * should returnActionResultFalse
     */
    public function test_save_calledWithKeyIsUnable_returnActionResultFalse()
    {
        $expected = new ActionResult(false,'Unable to save data in storage');
        $order = OrderMother::aJustOrder()->build();
        $this->redis_double->set(self::EMLINE_FETCH_KEY.$this->userId, $order->toJsonData())->willThrow(new RedisException('Unable to save data in storage'));
        $sut = $this->getSut();
        $actual = $sut->save($order->toJsonData(), $this->userId);
        $this->assertEquals($expected,$actual);
        
    }


    protected function getSut()
    {
        return new RedisOrderStorageStrategy($this->redis_double->reveal());
    }


}