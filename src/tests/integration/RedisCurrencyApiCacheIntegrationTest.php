<?php
namespace tests\integration;

use EuroMillions\web\services\external_apis\CurrencyConversion\RedisCurrencyApiCache;
use tests\base\RedisIntegrationTestBase;

class RedisCurrencyApiCacheIntegrationTest extends RedisIntegrationTestBase
{
    /** @var RedisCurrencyApiCache */
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new RedisCurrencyApiCache($this->redis);
    }

    /**
     * method getRate
     * when calledAfterSetRate
     * should returnInsertedValue
     */
    public function test_getRate_calledAfterSetRate_returnInsertedValue()
    {
        $rate = 1.83;
        $this->sut->setRate('EUR', 'USD', $rate);
        $this->assertEquals($rate, $this->sut->getRate('EUR', 'USD'));
    }

    /**
     * method getRate
     * when calledWithoutSetRate
     * should returnNull
     */
    public function test_getRate_calledWithoutSetRate_returnNull()
    {
        $this->assertNull($this->sut->getRate('EUR', 'USD'));
    }

    /**
     * method getConversionsToFetch
     * when calledAfterSeveralSetConversionFromBase
     * should returnProperValues
     */
    public function test_getConversionsToFetch_calledAfterSeveralSetConversionFromBase_returnProperValues()
    {
        $this->sut->setConversionFromBase('EUR', 'USD');
        $this->sut->setConversionFromBase('EUR', 'GBP');
        $this->sut->setConversionFromBase('AUD', 'CAD');
        $this->sut->setConversionFromBase('EUR', 'CAD');

        $expected = [
            'EUR' => ['USD', 'GBP', 'CAD'],
            'AUD' => ['CAD']
        ];

        $this->assertEquals($expected, $this->sut->getConversionsToFetch());
    }

    /**
     * method getConversionsToFetch
     * when calledWithoutSetConversionFromBase
     * should returnEmptyArray
     */
    public function test_getConversionsToFetch_calledWithoutSetConversionFromBase_returnEmptyArray()
    {
        $this->assertSame([], $this->sut->getConversionsToFetch());
    }
}