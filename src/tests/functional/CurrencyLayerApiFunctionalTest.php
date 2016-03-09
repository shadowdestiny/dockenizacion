<?php
namespace EuroMillions\tests\functional;

use EuroMillions\tests\base\DatabaseIntegrationTestBase;

class CurrencyLayerApiFunctionalTest extends DatabaseIntegrationTestBase
{
    /**
     * method testresult
     * when
     * should
     */
    public function test_testresult__()
    {
        $exurl = 'https://apilayer.net/api/live?access_key=802187a0471a2c43f41b1ff3f777d26c&source=EUR&currencies=GBP,USD,AUD,CAD,PLN,MXN';

        $ch = curl_init($exurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $json_response_content = curl_exec($ch);
        curl_close($ch);

        print_r($json_response_content);

        $exchangeRatesResult = json_decode($json_response_content, true );

        print_r($exchangeRatesResult);
    }

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }
}