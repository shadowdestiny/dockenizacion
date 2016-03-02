<?php
namespace tests\unit;

use EuroMillions\web\exceptions\ExternalServiceErrorException;
use EuroMillions\web\services\external_apis\YahooCurrencyApi;
use Money\Currency;
use Money\CurrencyPair;
use Prophecy\Argument;
use tests\base\UnitTestBase;
use tests\unit\utils\CurlResponse;

class YahooCurrencyApiUnitTest extends UnitTestBase
{
    const RESULT = '{"query":{"count":2,"created":"2015-06-22T13:57:48Z","lang":"en-US","diagnostics":{"url":[{"execution-start-time":"0","execution-stop-time":"3","execution-time":"3","content":"http://www.datatables.org/yahoo/finance/yahoo.finance.xchange.xml"},{"execution-start-time":"8","execution-stop-time":"11","execution-time":"3","content":"http://download.finance.yahoo.com/d/quotes.csv?s=EURGBP=X&f=snl1d1t1ab"},{"execution-start-time":"8","execution-stop-time":"12","execution-time":"4","content":"http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=snl1d1t1ab"}],"publiclyCallable":"true","cache":[{"execution-start-time":"7","execution-stop-time":"7","execution-time":"0","method":"GET","type":"MEMCACHED","content":"5372a187be7ec34f4e273188d21b0c77"},{"execution-start-time":"7","execution-stop-time":"7","execution-time":"0","method":"GET","type":"MEMCACHED","content":"ed2f4f98393df635b3389b9c25265749"}],"query":[{"execution-start-time":"7","execution-stop-time":"11","execution-time":"4","content":"select * from csv where url=\'http://download.finance.yahoo.com/d/quotes.csv?s=EURGBP=X&f=snl1d1t1ab\' and columns=\'Symbol,Name,Rate,Date,Time,Ask,Bid\'"},{"execution-start-time":"7","execution-stop-time":"12","execution-time":"5","content":"select * from csv where url=\'http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=snl1d1t1ab\' and columns=\'Symbol,Name,Rate,Date,Time,Ask,Bid\'"}],"javascript":[{"execution-start-time":"6","execution-stop-time":"11","execution-time":"5","instructions-used":"30662","table-name":"yahoo.finance.xchange"},{"execution-start-time":"6","execution-stop-time":"12","execution-time":"6","instructions-used":"37324","table-name":"yahoo.finance.xchange"}],"user-time":"13","service-time":"10","build-version":"0.2.154"},"results":{"rate":[{"id":"EURUSD","Name":"EUR/USD","Rate":"1.1373","Date":"6/22/2015","Time":"2:57pm","Ask":"1.1374","Bid":"1.1373"},{"id":"EURGBP","Name":"EUR/GBP","Rate":"0.7183","Date":"6/22/2015","Time":"2:57pm","Ask":"0.7183","Bid":"0.7182"}]}}}';
    const RESULT_ONE_CURRENCY = '{"query":{"count":1,"created":"2015-07-21T11:04:41Z","lang":"en-US","diagnostics":{"url":[{"execution-start-time":"0","execution-stop-time":"3","execution-time":"3","content":"http://www.datatables.org/yahoo/finance/yahoo.finance.xchange.xml"},{"execution-start-time":"7","execution-stop-time":"11","execution-time":"4","content":"http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=snl1d1t1ab"}],"publiclyCallable":"true","cache":{"execution-start-time":"6","execution-stop-time":"6","execution-time":"0","method":"GET","type":"MEMCACHED","content":"5372a187be7ec34f4e273188d21b0c77"},"query":{"execution-start-time":"6","execution-stop-time":"11","execution-time":"5","content":"select * from csv where url=\'http://download.finance.yahoo.com/d/quotes.csv?s=EURUSD=X&f=snl1d1t1ab\' and columns=\'Symbol,Name,Rate,Date,Time,Ask,Bid\'"},"javascript":{"execution-start-time":"5","execution-stop-time":"12","execution-time":"6","instructions-used":"18664","table-name":"yahoo.finance.xchange"},"user-time":"12","service-time":"7","build-version":"0.2.154"},"results":{"rate":{"id":"EURUSD","Name":"EUR/USD","Rate":"1.0861","Date":"7/21/2015","Time":"12:04pm","Ask":"1.0862","Bid":"1.0860"}}}}';
    const URL_FETCH_USD_COP_GBP = 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22EURUSD%22%2C%22EURCOP%22%2C%22EURGBP%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';
    const RESULT_ERROR = '{"error":{"lang":"en-US","diagnostics":{"url":{"execution-start-time":"1","execution-stop-time":"4","execution-time":"3","http-status-code":"502","http-status-message":"Connection refused","content":"http://www.datatables.org/yahoo/finance/yahoo.finance.xchange.xml"}},"description":"No definition found for Table yahoo.finance.xchange"}}';

    private $currencyApiCache_double;
    private $curlWrapper_double;

    private $eurGbpCurrencyPair;

    public function setUp()
    {
        $this->curlWrapper_double = $this->prophesize('\Phalcon\Http\Client\Provider\Curl');
        $this->currencyApiCache_double = $this->getInterfaceWebDouble('ICurrencyApiCacheStrategy');
        $this->eurGbpCurrencyPair = new CurrencyPair(new Currency('EUR'), new Currency('GBP'), 0.7183);

        parent::setUp();
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

        $this->currencyApiCache_double->setConversionRate(Argument::any())->willReturn(null);

        $this->curlWrapper_double->get(self::URL_FETCH_USD_COP_GBP)->willReturn($this->getResponse())->shouldBeCalledTimes(1);

        $this->exerciseGetRate('EUR', 'USD');
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

        $this->currencyApiCache_double->setConversionRate(Argument::any())->willReturn(null);
        $this->currencyApiCache_double->addConversionRateToFetch(Argument::any(), Argument::any())->willReturn(null);

        $this->curlWrapper_double->get(self::URL_FETCH_USD_COP_GBP)->willReturn($this->getResponse())->shouldBeCalledTimes(1);

        $this->exerciseGetRate('EUR', 'GBP');
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

        $this->curlWrapper_double->get(Argument::any())->willReturn($this->getResponse());
        $this->currencyApiCache_double->addConversionRateToFetch(Argument::any(), Argument::any())->willReturn(null);


        $this->currencyApiCache_double->setConversionRate(Argument::type('\Money\CurrencyPair'))->shouldBeCalledTimes(2);
        $this->currencyApiCache_double->setConversionRate(new CurrencyPair(new Currency('EUR'), new Currency('USD'), 1.1373))->shouldBeCalled();
        $this->currencyApiCache_double->setConversionRate($this->eurGbpCurrencyPair)->shouldBeCalled();

        $this->exerciseGetRate('EUR', 'GBP');
    }

    /**
     * method getRate
     * when calledWithNoCurrenciesToFetch
     * should setCurrencyInCurrenciesToFetch
     */
    public function test_getRate_calledWithNoCurrenciesToFetch_setCurrencyInCurrenciesToFetch()
    {
        $this->setCacheContentForConversionRate('EUR', 'GBP', null);
        $this->setCacheContentsForRatesToFetch(null);
        $this->curlWrapper_double->get(Argument::any())->willReturn($this->getResponse());
        $this->currencyApiCache_double->setConversionRate(Argument::any())->willReturn(null);

        $this->currencyApiCache_double->addConversionRateToFetch('EUR','GBP')->shouldBeCalled();
        $this->exerciseGetRate('EUR', 'GBP');
    }

    /**
     * method getRate
     * when calledWithCurrencyInCurrenciesToFetchInCache
     * should notCallAddConversionRateToFetch
     */
    public function test_getRate_calledWithCurrencyInCurrenciesToFetchInCache_notCallAddConversionRateToFetch()
    {
        $this->setCacheContentForConversionRate('EUR', 'GBP', null);
        $this->setCacheContentsForRatesToFetch([['EUR'=>'GBP']]);
        $this->curlWrapper_double->get(Argument::any())->willReturn($this->getResponse());
        $this->currencyApiCache_double->setConversionRate(Argument::any())->willReturn(null);

        $this->currencyApiCache_double->addConversionRateToFetch(Argument::any())->shouldNotBeCalled();
        $this->exerciseGetRate('EUR', 'GBP');
    }

    /**
     * method getRate
     * when calledWithRateInCache
     * should returnCacheResult
     */
    public function test_getRate_calledWithRateInCache_returnCacheResult()
    {
        $expected = new CurrencyPair(new Currency('EUR'), new Currency('GBP'), 1.3845);
        $this->setCacheContentForConversionRate('EUR', 'GBP', $expected);
        $actual = $this->exerciseGetRate('EUR', 'GBP');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getRate
     * when calledWithRateNotInCache
     * should storeRateInCacheAndReturnRate
     */
    public function test_getRate_calledWithRateNotInCache_storeRateInCacheAndReturnRate()
    {
        $expected = $this->eurGbpCurrencyPair;
        $this->setCacheContentForConversionRate('EUR', 'GBP', null);
        $this->setCacheContentsForRatesToFetch([['EUR'=>'GBP']]);
        $this->curlWrapper_double->get(Argument::any())->willReturn($this->getResponse());
        $this->currencyApiCache_double->setConversionRate(Argument::type('\Money\CurrencyPair'))->shouldBeCalledTimes(2);
        $this->currencyApiCache_double->setConversionRate($expected)->shouldBeCalled();
        $actual = $this->exerciseGetRate('EUR', 'GBP');
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getRate
     * when calledWithDifferentCurrencies
     * should callProperUrl
     * @dataProvider getCurrenciesAndUrls
     */
    public function test_getRate_calledWithDifferentCurrencies_callProperUrl($currencies, $url)
    {
        $this->setCacheContentForConversionRate('EUR', 'USD', null);
        $this->setCacheContentsForRatesToFetch($currencies);
        $this->iDontCareAboutCallsToCurrencyApiCache();
        $this->curlWrapper_double->get($url)->shouldBeCalledTimes(1)->willReturn($this->getResponse());
        $this->exerciseGetRate('EUR', 'USD');
    }

    public function getCurrenciesAndUrls()
    {
        return [
            [[['EUR'=>'USD']], 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22EURUSD%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='],
            [[['EUR'=>'USD'],['EUR'=>'GBP']], 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22EURUSD%22%2C%22EURGBP%22)&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='],
            [[['EUR'=>'USD'],['EUR'=>'COP'],['EUR'=>'GBP']], self::URL_FETCH_USD_COP_GBP],
        ];
    }

    /**
     * method getRate
     * when calledWithJustOneCurrencyToGet
     * should returnProperResult
     */
    public function test_getRate_calledWithJustOneCurrencyToGet_returnProperResult()
    {
        $expected = new CurrencyPair(new Currency('EUR'), new Currency('USD'), 1.0861);
        $this->setCacheContentForConversionRate('EUR', 'USD', null);
        $this->setCacheContentsForRatesToFetch(null);
        $this->curlWrapper_double->get(Argument::any())->willReturn($this->getResponse(self::RESULT_ONE_CURRENCY));
        $this->iDontCareAboutCallsToCurrencyApiCache();
        $actual = $this->exerciseGetRate('EUR', 'USD');
        $this->assertEquals($expected, $actual);

    }

    /**
     * method getRate
     * when calledWithSameCurrency
     * should throwInvalidArgumentException
     */
    public function test_getRate_calledWithSameCurrency_throwInvalidArgumentException()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $sut = $this->getSut();
        $sut->getRate(new Currency('EUR'),new Currency('EUR'));
    }

    /**
     * method getRate
     * when calledWithACurrencyNotInCacheAndRefreshFails
     * should throw
     */
    public function test_getRate_calledWithACurrencyNotInCacheAndRefreshFails_throw()
    {
        $this->setCacheContentForConversionRate('EUR', 'USD', null);
        $this->setCacheContentsForRatesToFetch(null);
        $this->curlWrapper_double->get(Argument::any())->willReturn($this->getResponse(self::RESULT_ERROR));
        $this->iDontCareAboutCallsToCurrencyApiCache();

        $sut = $this->getSut();
        $this->expectException(ExternalServiceErrorException::class);
        $this->expectExceptionMessage('No definition found for Table yahoo.finance.xchange');
        $sut->getRate(new Currency('EUR'), new Currency('USD'));
    }


    /**
     * @param $from
     * @param $to
     * @param $content
     */
    private function setCacheContentForConversionRate($from, $to, $content)
    {
        $this->currencyApiCache_double->getConversionRateFor(new Currency($from), new Currency($to))->willReturn($content);
    }

    /**
     * @param $rates_to_fetch
     */
    private function setCacheContentsForRatesToFetch($rates_to_fetch)
    {
        $this->currencyApiCache_double->getConversionRatesToFetch()->willReturn($rates_to_fetch);
    }

    /**
     * @param string $result
     * @return \stdClass
     */
    private function getResponse($result = self::RESULT)
    {
        return new CurlResponse($result);
    }

    /**
     * @return YahooCurrencyApi
     */
    private function getSut()
    {
        $sut = new YahooCurrencyApi($this->currencyApiCache_double->reveal(), $this->curlWrapper_double->reveal());
        return $sut;
    }

    /**
     * @param $currency_from
     * @param $currency_to
     * @return CurrencyPair
     */
    private function exerciseGetRate($currency_from, $currency_to)
    {
        $sut = $this->getSut();
        return $sut->getRate(new Currency($currency_from), new Currency($currency_to));
    }

    protected function iDontCareAboutCallsToCurrencyApiCache()
    {
        $this->currencyApiCache_double->addConversionRateToFetch(Argument::any(), Argument::any())->willReturn(null);
        $this->currencyApiCache_double->setConversionRate(Argument::any())->willReturn(null);
    }
}