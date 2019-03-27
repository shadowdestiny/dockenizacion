<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 18/03/19
 * Time: 02:01 PM
 */

namespace EuroMillions\tests\unit;

use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\web\services\external_apis\LotteryApisFactory;

class MegaSenaApiCest
{
    protected $jackpotApi;

    public function _before(\UnitTester $I)
    {
        $lottery = LotteryMother::aMegaSena();
        $lotteryApiFactory = new LotteryApisFactory();
        $this->jackpotApi = $lotteryApiFactory->jackpotApi($lottery);
    }

    /**
     * method getJackpotForDate
     * when is20190309
     * should returnsBetween10000000and100000000
     * @param UnitTester $I
     * @group megasena-api
     */
    public function test_getJackpotForDate_is20190309_returnsBetween10000000and100000000(\UnitTester $I)
    {
        $date = '2019-03-09';
        $jackpotMoney = $this->jackpotApi->getJackpotForDate('MegaSena', $date);
        $result = $jackpotMoney->getAmount() >= 10000000 &&  $jackpotMoney->getAmount() < 100000000;
        $I->assertTrue($result);
    }

    /**
     * method getJackpotForDate
     * when is20190316
     * should returnsMoreThan100000000
     * @param UnitTester $I
     * @group megasena-api
     */
    public function test_getJackpotForDate_is20190316_returnsMoreThan100000000(\UnitTester $I)
    {
        $date = '2019-03-16';
        $jackpotMoney = $this->jackpotApi->getJackpotForDate('MegaSena', $date);
        $result = $jackpotMoney->getAmount() >= 100000000;
        $I->assertTrue($result);
    }
}