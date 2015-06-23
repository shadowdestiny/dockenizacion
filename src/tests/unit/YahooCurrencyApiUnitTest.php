<?php
namespace tests\unit;

use EuroMillions\interfaces\ICurrencyApiCacheStrategy;
use EuroMillions\services\external_apis\YahooCurrencyApi;
use Money\Currency;
use Money\CurrencyPair;
use Phalcon\Http\Client\Provider\Curl;
use tests\base\UnitTestBase;

class YahooCurrencyApiUnitTest extends UnitTestBase
{
    const RESULT = '{"query":{"count":2,"created":"2015-06-22T13:57:48Z","lang":"en-US","diagnostics":{"url":[{"execution-start-time":"0","execution-stop-time":"3","execution-time":"3","content":"http://www.datatables.org/yahoo/finance/yahoo.finance.xchange.xml"},{"execution-start-time":"8","execution-stop-time":"11","execution-time":"3","content":"http://download.finance.yahoo.com/d/quotes.csv?s=EURGBP=X&f=snl1d1t1ab"},{"execution-start-time":"8","execution-stop-time":"12","execution-time":"4","content":"http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=snl1d1t1ab"}],"publiclyCallable":"true","cache":[{"execution-start-time":"7","execution-stop-time":"7","execution-time":"0","method":"GET","type":"MEMCACHED","content":"5372a187be7ec34f4e273188d21b0c77"},{"execution-start-time":"7","execution-stop-time":"7","execution-time":"0","method":"GET","type":"MEMCACHED","content":"ed2f4f98393df635b3389b9c25265749"}],"query":[{"execution-start-time":"7","execution-stop-time":"11","execution-time":"4","content":"select * from csv where url=\'http://download.finance.yahoo.com/d/quotes.csv?s=EURGBP=X&f=snl1d1t1ab\' and columns=\'Symbol,Name,Rate,Date,Time,Ask,Bid\'"},{"execution-start-time":"7","execution-stop-time":"12","execution-time":"5","content":"select * from csv where url=\'http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=snl1d1t1ab\' and columns=\'Symbol,Name,Rate,Date,Time,Ask,Bid\'"}],"javascript":[{"execution-start-time":"6","execution-stop-time":"11","execution-time":"5","instructions-used":"30662","table-name":"yahoo.finance.xchange"},{"execution-start-time":"6","execution-stop-time":"12","execution-time":"6","instructions-used":"37324","table-name":"yahoo.finance.xchange"}],"user-time":"13","service-time":"10","build-version":"0.2.154"},"results":{"rate":[{"id":"EURUSD","Name":"EUR/USD","Rate":"1.1373","Date":"6/22/2015","Time":"2:57pm","Ask":"1.1374","Bid":"1.1373"},{"id":"EURGBP","Name":"EUR/GBP","Rate":"0.7183","Date":"6/22/2015","Time":"2:57pm","Ask":"0.7183","Bid":"0.7182"}]}}}';
    const URL_FETCH_USD_COP_GBP = 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22EURUSD%22%2C%22EURCOP%22%2C%22EURGBP%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';

    /** @var  Curl|\PHPUnit_Framework_MockObject_MockObject */
    private $curlWrapper_double;
    /** @var  ICurrencyApiCacheStrategy|\PHPUnit_Framework_MockObject_MockObject */
    private $currencyApiCache_double;
    /** @var  YahooCurrencyApi */
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $this->currencyApiCache_double = $this->getMockBuilder('\EuroMillions\interfaces\ICurrencyApiCacheStrategy')->getMock();
        $this->curlWrapper_double = $this->getMockBuilder('\Phalcon\Http\Client\Provider\Curl')->getMock();
        $this->sut = new YahooCurrencyApi($this->currencyApiCache_double, $this->curlWrapper_double);
    }

    /**
     * method getRate
     * when calledWithResultNotInCache
     * should refreshAllTheConversions
     */
    public function test_getRate_calledWithResultNotInCache_refreshAllTheConversions()
    {
        $this->setCacheContentForConversionRate('EUR', 'USD', null);
        $this->setCacheContentsForRatesToFetch([['EUR' => 'USD'], ['EUR' => 'COP'], ['EUR' => 'GBP']]);

        $this->curlWrapper_double->expects($this->once())
            ->method('get')
            ->with(self::URL_FETCH_USD_COP_GBP);

        $this->sut->getRate('EUR', 'USD');
    }

    /**
     * method getRate
     * when calledWithResultNotInCacheAndConversionNotInCache
     * should refreshAllTheConversions
     */
    public function test_getRate_calledWithResultNotInCacheAndConversionNotInCache_refreshAllTheConversions()
    {
        $this->setCacheContentForConversionRate('EUR', 'GBP', null);
        $this->setCacheContentsForRatesToFetch([['EUR'=>'USD'], ['EUR'=>'COP']]);

        $this->curlWrapper_double->expects($this->once())
            ->method('get')
            ->with(self::URL_FETCH_USD_COP_GBP);
        $this->sut->getRate('EUR', 'GBP');
    }

    /**
     * method getRate
     * when calledWithResultNotInCache
     * should storeAllConversionRatesInCache
     */
    public function test_getRate_calledWithResultNotInCache_storeAllConversionRatesInCache()
    {
        $this->setCacheContentForConversionRate('EUR', 'GBP', null);
        $this->setCacheContentsForRatesToFetch([['EUR'=>'USD']]);
        $response = new \stdClass();
        $response->body = self::RESULT;
        $this->curlWrapper_double->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));

        $this->currencyApiCache_double->expects($this->at(2))
            ->method('setConversionRate')
            ->with(new CurrencyPair(new Currency('EUR'), new Currency('USD'), 1.1373));
        $this->currencyApiCache_double->expects($this->at(3))
            ->method('setConversionRate')
            ->with(new CurrencyPair(new Currency('EUR'), new Currency('GBP'), 0.7183));
        $this->sut->getRate('EUR', 'GBP');
    }


    //EMTEST currencies to fetch está vacío
    //EMTEST rate estaba en cache
    //EMTEST todos los rates se almacenan en cache


    /**
     * method getRates
     * when calledWithDifferentCurrencies
     * should callProperUrl
     * @dataProvider getCurrenciesAndUrls
     * @param array $currencies
     * @param string $url
     */
    public function test_getRates_calledWithDifferentCurrencies_callProperUrl($currencies, $url)
    {
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject $curlWrapper_mock */
        $curlWrapper_mock = $this->getMockBuilder('\Phalcon\Http\Client\Provider\Curl')->getMock();
        $curlWrapper_mock->expects($this->once())
            ->method('get')
            ->with($url);
        $sut = new YahooCurrencyApi($curlWrapper_mock);
        $sut->getRates($currencies);
    }

    public function getCurrenciesAndUrls()
    {
        return [
            [['USD'], 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22EURUSD%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='],
            [['USD','GBP'], 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22EURUSD%22%2C%22EURGBP%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='],
            [['USD','COP','GBP'], self::URL_FETCH_USD_COP_GBP],
        ];
    }

    /**
     * method getRates
     * when called
     * should returnArrayOfRates
     */
    public function test_getRates_called_returnArrayOfRates()
    {
        $expected = [
            new CurrencyPair(
                new Currency('EUR'),
                new Currency('USD'),
                1.1373
            ),
            new CurrencyPair(
                new Currency('EUR'),
                new Currency('GBP'),
                0.7183
            )
        ];
        $response = new \stdClass();
        $response->body = self::RESULT;
        /** @var Curl|\PHPUnit_Framework_MockObject_MockObject $curlWrapper_stub */
        $curlWrapper_stub = $this->getMockBuilder('\Phalcon\Http\Client\Provider\Curl')->getMock();
        $curlWrapper_stub->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));
        $sut = new YahooCurrencyApi($curlWrapper_stub);
        $actual = $sut->getRates(['USD', 'GBP']);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $from
     * @param $to
     * @param $content
     */
    private function setCacheContentForConversionRate($from, $to, $content)
    {
        $this->currencyApiCache_double->expects($this->any())
            ->method('getConversionRateFor')
            ->with(new Currency($from), new Currency($to))
            ->will($this->returnValue($content));
    }

    /**
     * @param $rates_to_fetch
     */
    private function setCacheContentsForRatesToFetch($rates_to_fetch)
    {
        $this->currencyApiCache_double->expects($this->any())
            ->method('getConversionRatesToFetch')
            ->will($this->returnValue($rates_to_fetch));
    }
}