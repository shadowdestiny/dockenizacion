<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\services\external_apis\CurrencyConversion\NullCurrencyApiCache;
use EuroMillions\tests\base\UnitTestBase;

class NullCurrencyApiCacheUnitTest extends UnitTestBase
{
    /**
     * method getConversionsToFetch
     * when calledAfterSetConversionFromBase
     * should returnExactlyThatConversion
     */
    public function test_getConversionsToFetch_calledAfterSetConversionFromBase_returnExactlyThatConversion()
    {
        $sut = new NullCurrencyApiCache();
        $sut->setConversionFromBase('EUR', 'USD');
        $this->assertEquals(['EUR'=>['USD']], $sut->getConversionsToFetch());
    }
}