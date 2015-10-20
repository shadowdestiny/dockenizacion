<?php


namespace tests\unit;


use EuroMillions\NullCypher;
use EuroMillions\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\vo\OperationKey;
use tests\base\LotteryValidationCastilloRelatedTest;
use tests\base\UnitTestBase;

class LotteryValidationCastilloApiUnitTest extends UnitTestBase
{

    use LotteryValidationCastilloRelatedTest;

    /**
     * method request
     * when called
     * should getResponseMessageFromProvider
     */
    public function test_request_called_getResponseMessageFromProvider()
    {
        $key = new OperationKey('', new NullCypher());
        $curlWrapper_stub = $this->getCurlWrapperWithXmlRequest();
        $sut = new LotteryValidationCastilloApi($curlWrapper_stub);
        $sut->request();
    }
    
    
}