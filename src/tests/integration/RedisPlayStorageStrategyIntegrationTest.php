<?php


namespace tests\integration;


use EuroMillions\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\LastDrawDate;
use EuroMillions\vo\PlayFormToStorage;
use EuroMillions\vo\UserId;
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
        $key = self::EMLINE_FETCH_KEY . ':'. $this->userId->id();
        $expected = $this->getPlayFormToStorage();
        $this->sut->saveAll($expected,$this->userId);
        $actual = $this->sut->findByKey($key);
        $this->assertEquals($expected->toJson(),$actual->getValues());
    }

}