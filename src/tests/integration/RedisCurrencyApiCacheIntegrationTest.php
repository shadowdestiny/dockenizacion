<?php
namespace tests\integration;

use EuroMillions\web\services\external_apis\RedisCurrencyApiCache;
use Money\Currency;
use Money\CurrencyPair;
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
     * method setConversionRatesToFetch
     * when called
     * should storesTheRatesOnCache
     */
    public function test_setConversionRatesToFetch_called_storesTheRatesOnCache()
    {
        $expected = [['EUR'=>'USD'], ['EUR'=> 'COP']];
        $this->sut->setConversionRatesToFetch($expected);
        $actual = $this->redis->get(RedisCurrencyApiCache::RATES_TO_FETCH_KEY);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getConversionRatesToFetch
     * when calledWithoutPreviousSet
     * should returnNull
     */
    public function test_getConversionRatesToFetch_calledWithoutPreviousSet_returnNull()
    {
        $actual = $this->sut->getConversionRatesToFetch();
        $this->assertEquals(null, $actual);
    }

    /**
     * method getConversionRatesToFetch
     * when calledAfterASet
     * should returnsSettedValue
     */
    public function test_getConversionRatesToFetch_calledAfterASet_returnsSettedValue()
    {
        $expected = [['USD' => 'EUR']];
        $this->sut->setConversionRatesToFetch($expected);
        $actual = $this->sut->getConversionRatesToFetch();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getConversionRateFor
     * when calledWithoutPreviousSet
     * should returnNull
     */
    public function test_getConversionRateFor_calledWithoutPreviousSet_returnNull()
    {
        $actual = $this->sut->getConversionRateFor(new Currency('EUR'), new Currency('USD'));
        $this->assertEquals(null, $actual);
    }

    /**
     * method getConversionRateFor
     * when calledAfterASet
     * should returnSettedValue
     */
    public function test_getConversionRateFor_calledAfterASet_returnSettedValue()
    {

        $expected = new CurrencyPair(new Currency('EUR'), new Currency('USD'), 6.66);
        $this->sut->setConversionRate($expected);
        $this->sut->setConversionRate(new CurrencyPair(new Currency('EUR'), new Currency('COP'), 5.66));
        $actual = $this->sut->getConversionRateFor(new Currency('EUR'), new Currency('USD'));
        $this->assertEquals($expected, $actual);
    }

    /**
     * method setConversionRate
     * when called
     * should storeTheRateOnCache
     */
    public function test_setConversionRate_called_storeTheRateOnCache()
    {
        $currency_from = new Currency('EUR');
        $currency_to = new Currency('USD');
        $expected = new CurrencyPair($currency_from, $currency_to, 0.666);
        $this->sut->setConversionRate($expected);
        $actual = $this->redis->get(RedisCurrencyApiCache::getRateKey($currency_from, $currency_to));
        $this->assertEquals($expected, $actual);
    }

    /**
     * method addConversionRateToFetch
     * when calledAfterASet
     * should returnResultContainingOldAndNewConversions
     */
    public function test_addConversionRateToFetch_calledAfterASet_returnResultContainingOldAndNewConversions()
    {
        $this->sut->setConversionRatesToFetch([['EUR'=>'USD'], ['EUR'=> 'COP']]);
        $this->sut->addConversionRateToFetch('USD', 'EUR');
        $actual = $this->redis->get(RedisCurrencyApiCache::RATES_TO_FETCH_KEY);
        $this->assertEquals([['EUR'=>'USD'], ['EUR'=> 'COP'], ['USD' => 'EUR']], $actual);
    }

    /**
     * method addConversionRateToFetch
     * when calledWithoutPreviousSet
     * should returnConversionAdded
     */
    public function test_addConversionRateToFetch_calledWithoutPreviousSet_returnConversionAdded()
    {
        $this->sut->addConversionRateToFetch('USD', 'COP');
        $actual = $this->redis->get(RedisCurrencyApiCache::RATES_TO_FETCH_KEY);
        $this->assertEquals([['USD'=>'COP']], $actual);
    }
}