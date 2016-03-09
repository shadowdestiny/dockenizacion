<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\external_apis\CurrencyConversion\CurrencyLayerApi;
use Phalcon\Http\Client\Response;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class CurrencyLayerApiUnitTest extends UnitTestBase
{
    const API_KEY = 'apikey';
    const API_URL = 'https://apilayer.net/api/live';
    const RESULT_OK1 = '{"success":true,"terms":"https:\/\/currencylayer.com\/terms","privacy":"https:\/\/currencylayer.com\/privacy","timestamp":1457002875,"source":"EUR","quotes":{"EURUSD":1,"EURGBP":2,"EURCOP":3}}';
    const RESULT_OK2 = '{"success":true,"terms":"https:\/\/currencylayer.com\/terms","privacy":"https:\/\/currencylayer.com\/privacy","timestamp":1457002875,"source":"GBP","quotes":{"GBPAUD":4,"GBPINR":5}}';
    const RESULT_OK3 = '{"success":true,"terms":"https:\/\/currencylayer.com\/terms","privacy":"https:\/\/currencylayer.com\/privacy","timestamp":1457002875,"source":"USD","quotes":{"USDCAD":6,"USDEUR":0.98}}';
    protected $curl_double;
    protected $cache_double;


    const CUR_FROM = 'USD';

    const CUR_TO = 'EUR';

    const RATE = '0.98';

    public function setUp()
    {
        $this->curl_double = $this->prophesize('\Phalcon\Http\Client\Provider\Curl');
        $this->cache_double = $this->getInterfaceWebDouble('ICurrencyApiCacheStrategy');

        parent::setUp();
    }

    /**
     * method getRate
     * when calledWithRateOnCache
     * should returnCachedResultAndDoNotCallApi
     */
    public function test_getRate_calledWithRateOnCache_returnCachedResultAndDoNotCallApi()
    {
        $this->cache_double->getRate(self::CUR_FROM, self::CUR_TO)->willReturn(self::RATE);
        $this->curl_double->get(Argument::any())->shouldNotBeCalled();
        $actual = $this->exerciseGetRate();
        $this->assertEquals(self::RATE, $actual);
    }

    /**
     * method getRate
     * when calledWithRateNotInCache
     * should addConversionToListToFetchAndReturnRateFromApiAndStoreItInCache
     */
    public function test_getRate_calledWithRateNotInCache_addConversionToListToFetchAndReturnRateFromApiAndStoreItInCache()
    {
        $this->cache_double->getRate(self::CUR_FROM, self::CUR_TO)->willReturn(null);
        $this->cache_double->getConversionsToFetch()->willReturn([
            'EUR' => ['USD', 'GBP', 'COP'],
            'GBP' => ['AUD', 'INR'],
            'USD' => ['CAD', 'EUR']
        ]);
        $this->cache_double->setConversionFromBase(self::CUR_FROM, self::CUR_TO)->shouldBeCalled();
        $this->curl_double->get(self::API_URL.'?access_key='.self::API_KEY.'&source=EUR&currencies=USD,GBP,COP')->willReturn($this->getResponseObject(self::RESULT_OK1));
        $this->curl_double->get(self::API_URL.'?access_key='.self::API_KEY.'&source=GBP&currencies=AUD,INR')->willReturn($this->getResponseObject(self::RESULT_OK2));
        $this->curl_double->get(self::API_URL.'?access_key='.self::API_KEY.'&source=USD&currencies=CAD,EUR')->willReturn($this->getResponseObject(self::RESULT_OK3));
        $this->cache_double->setRate('EUR', 'USD', 1)->shouldBeCalled();
        $this->cache_double->setRate('EUR', 'GBP', 2)->shouldBeCalled();
        $this->cache_double->setRate('EUR', 'COP', 3)->shouldBeCalled();
        $this->cache_double->setRate('GBP', 'AUD', 4)->shouldBeCalled();
        $this->cache_double->setRate('GBP', 'INR', 5)->shouldBeCalled();
        $this->cache_double->setRate('USD', 'CAD', 6)->shouldBeCalled();
        $this->cache_double->setRate(self::CUR_FROM, self::CUR_TO, self::RATE)->shouldBeCalled();
        $actual = $this->exerciseGetRate();
        $this->assertEquals(self::RATE, $actual);
    }

   /**
     * @return CurrencyLayerApi
     */
    private function getSut()
    {
        $sut = new CurrencyLayerApi(self::API_KEY, $this->curl_double->reveal(), $this->cache_double->reveal());
        return $sut;
    }

    private function exerciseGetRate()
    {
        $sut = $this->getSut();
        return $sut->getRate(self::CUR_FROM, self::CUR_TO);
    }

    private function getResponseObject($result)
    {
        $response = new Response();
        $response->body = $result;
        return $response;
    }
}