<?php


namespace tests\unit;


use EuroMillions\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\ServiceActionResult;
use RedisException;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class RedisPlayStorageStrategyUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $redis_double;

    public function setUp()
    {
        $this->redis_double = $this->getInterfaceDouble('IRedis');
        parent::setUp();
    }

    /**
     * method saveAll
     * when calledWithArrayEuroMillionsLine
     * should setEuroMillionsLineArrayInRedis
     */
    public function test_saveAll_calledWithArrayEuroMillionsLine_setEuroMillionsLineArrayInRedis()
    {
        $sut = $this->getSut();
        $this->redis_double->save(RedisPlayStorageStrategy::EMLINE_FETCH_KEY,$this->getEuroMillionsLine())->shouldBeCalled();
        $sut->saveAll($this->getEuroMillionsLine());
    }

    /**
     * method findByKey
     * when calledWithKeyInRedisStorage
     * should returnServiceResultActionTrueAndEuroMillionsLineArray
     */
    public function test_findByKey_calledWithKeyInRedisStorage_returnServiceResultActionTrueAndEuroMillionsLineArray()
    {
        $excepted = new ServiceActionResult(true, $this->getEuroMillionsLine());
        $sut = $this->getSut();
        $this->redis_double->get(RedisPlayStorageStrategy::EMLINE_FETCH_KEY)->willReturn($this->getEuroMillionsLine());
        $actual = $sut->findByKey(RedisPlayStorageStrategy::EMLINE_FETCH_KEY);
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method findByKey
     * when keyNotFoundInRedisStore
     * should returnServiceActionResultFalse
     */
    public function test_findByKey_keyNotFoundInRedisStore_returnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false,'Key not found');
        $sut = $this->getSut();
        $this->redis_double->get(RedisPlayStorageStrategy::EMLINE_FETCH_KEY)->willReturn(null);
        $actual = $sut->findByKey(RedisPlayStorageStrategy::EMLINE_FETCH_KEY);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method remove
     * when called
     * should removeEuroMillionsLineArrayFromRedisStorage
     */
    public function test_remove_called_removeEuroMillionsLineArrayFromRedisStorage()
    {
        $sut = $this->getSut();
        $this->redis_double->delete(RedisPlayStorageStrategy::EMLINE_FETCH_KEY)->shouldBeCalled();
        $sut->delete(RedisPlayStorageStrategy::EMLINE_FETCH_KEY);
    }

    /**
     * method remove
     * when calledWithEmptyKey
     * should returnServiceActionResultFalseAndNotRemove
     */
    public function test_remove_calledWithEmptyKey_returnServiceActionResultFalseAndNotRemove()
    {
        $expected = new ServiceActionResult(false,'Invalid key');
        $sut = $this->getSut();
        $actual = $sut->delete();
        $this->assertEquals($expected,$actual);
    }

    /**
     * method remove
     * when calledWithValidKey
     * should returnServiceActionResultTrue
     */
    public function test_remove_calledWithValidKey_returnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true);
        $sut = $this->getSut();
        $actual  = $sut->delete(RedisPlayStorageStrategy::EMLINE_FETCH_KEY);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method remove
     * when calledWithValidKey
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_remove_calledWithValidKey_throwExceptionAndReturnServiceActionResultFalse()
    {
        $excepted = new ServiceActionResult(false,'An exception ocurred while delete key');
        $sut = $this->getSut();
        $this->redis_double->delete(RedisPlayStorageStrategy::EMLINE_FETCH_KEY)->willThrow(new RedisException('An exception ocurred while delete key'));
        $actual = $sut->delete(RedisPlayStorageStrategy::EMLINE_FETCH_KEY);
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method findByKey
     * when calledWithValidKey
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_findByKey_calledWithValidKey_throwExceptionAndReturnServiceActionResultFalse()
    {
        $excepted = new ServiceActionResult(false,'An error ocurred while find key');
        $sut = $this->getSut();
        $this->redis_double->get(RedisPlayStorageStrategy::EMLINE_FETCH_KEY)->willThrow(new RedisException('An error ocurred while find key'));
        $actual = $sut->findByKey(RedisPlayStorageStrategy::EMLINE_FETCH_KEY);
        $this->assertEquals($excepted,$actual);
    }

    /**
     * method saveAll
     * when calledWithValidEuroMillionsArray
     * should throwExceptionAndReturnServiceActionResultFalse
     */
    public function test_saveAll_calledWithValidEuroMillionsArray_throwExceptionAndReturnServiceActionResultFalse()
    {
        $expected = new ServiceActionResult(false,'Unable to save data in storage');
        $sut = $this->getSut();
        $this->redis_double->save(RedisPlayStorageStrategy::EMLINE_FETCH_KEY,$this->getEuroMillionsLine())->willThrow(new RedisException('Unable to save data in storage'));
        $actual = $sut->saveAll($this->getEuroMillionsLine());
        $this->assertEquals($expected,$actual);
    }


    protected function getSut()
    {
        $sut = new RedisPlayStorageStrategy($this->redis_double->reveal());
        return $sut;
    }


    private function getEuroMillionsLine()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];

        $r_numbers = $this->getRegularNumbers($regular_numbers);
        $l_numbers = $this->getLuckyNumbers($lucky_numbers);

        $euroMillionsLine = [
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers)
        ];
        return $euroMillionsLine;
    }


}