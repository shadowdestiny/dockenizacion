<?php


namespace tests\integration;


use EuroMillions\services\play_strategies\RedisPlayStorageStrategy;
use EuroMillions\vo\EuroMillionsLine;
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
        $this->sut = new RedisPlayStorageStrategy($this->redis,$this->userId);
    }

    /**
     * method saveAll
     * when calledWithEuroMillionsLineArray
     * should storeCorrectlyOnCache
     */
    public function test_saveAll_calledWithEuroMillionsLineArray_storeCorrectlyOnCache()
    {
        $key = self::EMLINE_FETCH_KEY . ':'. $this->userId->id();
        $expected = $this->getEuroMillionsLine();
        $this->sut->saveAll($expected);
        $actual = $this->sut->findByKey($key);
        $this->assertEquals($expected,$actual->getValues());
    }

    /**
     * @return array
     */
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