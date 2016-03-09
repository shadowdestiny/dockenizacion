<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\entities\Lottery;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\tests\base\UnitTestBase;

class LotteryApisFactoryUnitTest extends UnitTestBase
{
    /**
     * method jackpotApi
     * when called
     * should returnProperJackpotApi
     */
    public function test_jackpotApi_called_returnProperJackpotApi()
    {
        $api_in_db = 'LoteriasyapuestasDotEs';
        $expected_jackpot_api = $api_in_db.'Api';
        $lottery = new Lottery();
        $lottery->initialize([
            'jackpot_api' => $api_in_db
        ]);
        $sut = new LotteryApisFactory();
        $api = $sut->jackpotApi($lottery);
        $this->assertInstanceOf('\Euromillions\web\services\external_apis\\' . $expected_jackpot_api, $api);
    }

    /**
     * method resultApi
     * when called
     * should returnProperResultApi
     */
    public function test_resultApi_called_returnProperResultApi()
    {
        $api_in_db = 'LoteriasyapuestasDotEs';
        $expected_result_api = $api_in_db.'Api';
        $lottery = new Lottery();
        $lottery->initialize([
            'result_api' => $api_in_db
        ]);
        $sut = new LotteryApisFactory();
        $api = $sut->resultApi($lottery);
        $this->assertInstanceOf('\Euromillions\web\services\external_apis\\' . $expected_result_api, $api);
    }
}