<?php
namespace tests\unit;

use EuroMillions\entities\Lottery;
use EuroMillions\services\external_apis\LotteryApisFactory;
use tests\base\UnitTestBase;

class LotteryApisFactoryUnitTest extends UnitTestBase
{
    /**
     * method jackpotApi
     * when called
     * should returnProperJackpotApi
     */
    public function test_jackpotApi_called_returnProperJackpotApi()
    {
        $jackpot_api = 'LoteriasyapuestasDotEsApi';
        $lottery = new Lottery();
        $lottery->initialize([
            'jackpot_api' => $jackpot_api
        ]);
        $sut = new LotteryApisFactory();
        $api = $sut->jackpotApi($lottery);
        $this->assertInstanceOf('\Euromillions\services\external_apis\\' . $jackpot_api, $api);
    }
}