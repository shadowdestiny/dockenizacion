<?php
namespace tests\functional;

use EuroMillions\services\external_apis\YahooCurrencyApi;
use tests\base\UnitTestBase;

class YahooCurrencyApiFunctionalTest extends UnitTestBase
{



    /**
     * method
     * when
     * should
     */
    public function test_vamos_a_testear()
    {
        $sut = new YahooCurrencyApi();
        $sut->getRates(['USD','GBP']);
    }
}