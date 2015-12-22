<?php


namespace tests\integration;


use EuroMillions\web\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\web\vo\UserId;
use Phalcon\Di;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\RedisIntegrationTestBase;

class RedisPlayStorageStrategyIntegrationTest extends RedisIntegrationTestBase
{

    use EuroMillionsResultRelatedTest;

    /** @var  RedisPlayStorageStrategy */
    private $sut;

    private $userId;

    const EMLINE_FETCH_KEY = 'PlayStore_EMLINES';

    public function setUp()
    {
        parent::setUp();
        $this->userId = UserId::create();
        $this->sut = new RedisPlayStorageStrategy($this->redis);
    }

    /**
     * method saveAll
     * when calledWithEuroMillionsLineArray
     * should storeCorrectlyOnCache
     */
    public function test_saveAll_calledWithEuroMillionsLineArray_storeCorrectlyOnCache()
    {
        $playFormStorage = $this->getPlayFormToStorage();
        $this->sut->saveAll($playFormStorage,$this->userId);
        $expected = $this->getPlayFormToStorage()->toJson();
        $actual = $this->sut->findByKey($this->userId->id());
        $this->assertEquals($expected,$actual->getValues());
    }

}