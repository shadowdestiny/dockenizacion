<?php


namespace tests\old_functional;


use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\services\card_payment_providers\widecard\GatewayClientWrapper;
use EuroMillions\web\services\card_payment_providers\widecard\WideCardConfig;
use Phalcon\Config;

class WideCardGatewayClientWrapperFunctionalTest extends DatabaseIntegrationTestBase
{

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }


    /**
     * method send
     * when calledPassingAValidJson
     * should returnJsonResponseFromEndpoint
     */
    public function test_send_calledPassingAValidJson_returnJsonResponseFromEndpoint()
    {
        $endpoint = 'https://api.euromillions.com/beta/wirecard-payment';
        $api_key = 'n6CNSj6sue1EF2X9MLm9E1Pf873RrtYVYnhXsrI4';
        $wideCardConfig = new WideCardConfig($endpoint,$api_key);
        $gwWrapper = new GatewayClientWrapper($wideCardConfig);
        $json = [
                	"idTransaction" => 1,
	                "userId" => "ea76282a-502e-41dd-a431-2f54bb65e017",
	                "amount" => 785,
	                "creditCardNumber" => "4200000000000000",
	                "cvc" => "000",
	                "expirationYear" => "2019",
	                "expirationMonth" => "10",
	                "cardHolderName" => "Test1 Test2",
	                "ip" => "172.16.0.1"
                  ];
        $actual = $gwWrapper->send($json);
        $actualRaw = json_decode($actual->body);
        $this->assertEquals("200",$actual->header->statusCode);
        $this->assertEquals("ok",$actualRaw->status);
    }


}