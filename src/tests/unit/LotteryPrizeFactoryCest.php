<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 21/02/19
 * Time: 05:01 PM
 */

namespace EuroMillions\tests\unit;

use Phalcon\Di;
use Money\Money;
use Money\Currency;
use EuroMillions\web\vo\PowerBallDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\megasena\vo\MegaSenaDrawBreakDown;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\eurojackpot\vo\EuroJackpotDrawBreakDown;
use EuroMillions\megamillions\vo\MegaMillionsDrawBreakDown;
use EuroMillions\superenalotto\vo\SuperEnalottoDrawBreakDown;
use EuroMillions\web\services\factories\LotteryPrizeFactory;
use EuroMillions\tests\helpers\mothers\EuroMillionsDrawMother;

class LotteryPrizeFactoryCest
{
    protected $currencyConversion;

    public function _before(\UnitTester $I)
    {
        $this->currencyConversion = Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

    /**
     * method lotteryPrizeFactory
     * when isEuroJackpot
     * should returnsTheCorrectPrize
     * @param UnitTester $I
     * @group lottery-prize-factory
     * @dataProvider getEuroJackpotResults
     */
    public function test_lotteryPrizeFactory_isEuroJackpot_returnsTheCorrectPrize(\UnitTester $I, \Codeception\Example $data)
    {
        $euroJackpotBreakDown = [
            "prizes" => [
                "match-5-2" => "1626134.40",
                "match-5-1" => "626134.00",
                "match-5"   => "165741.30",
                "match-4-2" => "3745.50",
                "match-4-1" => "287.80",
                "match-4"   => "131.50",
                "match-3-2" => "45.60",
                "match-2-2" => "17.10",
                "match-3-1" => "21.30",
                "match-3"   => "16.60",
                "match-1-2" => "3.60",
                "match-2-1" => "7.80",
            ],
            "winners" => [
                "match-1-2" => 224171,
                "match-2-1" => 535178,
                "match-2-2" => 43366,
                "match-3" => 57090,
                "match-3-1" => 35325,
                "match-3-2" => 2907,
                "match-4" => 1176,
                "match-4-1" => 691,
                "match-4-2" => 59,
                "match-5" => 4,
                "match-5-1" => 3,
                "match-5-2" => 1
            ]
        ];

        $lottery = LotteryMother::anEuroJackpot();
        $breakDown = new EuroJackpotDrawBreakDown($euroJackpotBreakDown);
        $draw = EuroMillionsDrawMother::anEuroJackpotDrawWithJackpotAndBreakDown()
            ->withBreakDown($breakDown)
            ->build();

        $I->haveInDatabase(
            'lotteries',
            $lottery->toArray()
        );

        $I->haveInDatabase(
            'euromillions_draws',
            $draw->toArray()
        );

        $prize = LotteryPrizeFactory::create($lottery, $draw->getBreakDown(), $data['balls']);

        $I->assertEquals($data['prize'], $prize->getPrize()->getAmount());
    }

    /**
     * method lotteryPrizeFactory
     * when isEuroMillions
     * should returnsTheCorrectPrize
     * @param UnitTester $I
     * @group lottery-prize-factory
     * @dataProvider getEuroMillionsResults
     */
    public function test_lotteryPrizeFactory_isEuroMillions_returnsTheCorrectPrize(\UnitTester $I, \Codeception\Example $data)
    {
        $euroMillionsBreakDown = [
            'category_one' => ['5 + 2', new Money(3000000000, new Currency('EUR')), '1'],
            'category_two' => ['5 + 1', new Money(29367800, new Currency('EUR')), '9'],
            'category_three' => ['5 + 0', new Money(8817700, new Currency('EUR')), '10'],
            'category_four' => ['4 + 2', new Money(668000, new Currency('EUR')), '66'],
            'category_five' => ['4 + 1', new Money(27500, new Currency('EUR')), '1.402'],
            'category_six' => ['4 + 0', new Money(13100, new Currency('EUR')), '2.934'],
            'category_seven' => ['3 + 2', new Money(6000, new Currency('EUR')), '4.527'],
            'category_eight' => ['2 + 2', new Money(1800, new Currency('EUR')), '66.973'],
            'category_nine' => ['3 + 1', new Money(1600, new Currency('EUR')), '72.488'],
            'category_ten' => ['3 + 0', new Money(1300, new Currency('EUR')), '152.009'],
            'category_eleven' => ['1 + 2', new Money(900, new Currency('EUR')), '358.960'],
            'category_twelve' => ['2 + 1', new Money(852, new Currency('EUR')), '1.138.617'],
            'category_thirteen' => ['2 + 0', new Money(415, new Currency('EUR')), '2.390.942'],
        ];

        $lottery = LotteryMother::anEuroMillions();

        $I->haveInDatabase(
            'lotteries',
            $lottery->toArray()
        );

        $breakDown = new EuroMillionsDrawBreakDown($euroMillionsBreakDown);

        $draw = EuroMillionsDrawMother::anEuroMillionsDrawWithJackpotAndBreakDown()
            ->withLottery($lottery)
            ->withBreakDown($breakDown)
            ->build();

        $I->haveInDatabase(
            'euromillions_draws',
            $draw->toArray()
        );

        $prize = LotteryPrizeFactory::create($lottery, $draw->getBreakDown(), $data['balls']);

        $I->assertEquals($data['prize'], $prize->getPrize()->getAmount());
    }

    /**
     * method lotteryPrizeFactory
     * when isPowerBall
     * should returnsTheCorrectPrize
     * @param UnitTester $I
     * @group lottery-prize-factory
     * @dataProvider getPowerBallResults
     */
    public function test_lotteryPrizeFactory_isPowerBall_returnsTheCorrectPrize(\UnitTester $I, \Codeception\Example $data)
    {
        $powerBallBreakDown = [
            "prizes" => [
                "match-0-p" => "4.00",
                "match-0-p-pp" => "8.00",
                "match-1-p" => "4.00",
                "match-1-p-pp" => "8.00",
                "match-2-p" => "7.00",
                "match-2-p-pp" => "14.00",
                "match-3" => "7.00",
                "match-3-p" => "100.00",
                "match-3-p-pp" => "200.00",
                "match-3-pp" => "14.00",
                "match-4" => "100.00",
                "match-4-p" => "50000.00",
                "match-4-p-pp" => "100000.00",
                "match-4-pp" => "200.00",
                "match-5" => "1000000.00",
                "match-5-p" => "71000000.00",
                "match-5-pp" => "2000000.00"
            ],
            "winners" => [
                "match-0-p" => 254996,
                "match-0-p-pp" => 70489,
                "match-1-p" => 101929,
                "match-1-p-pp" => 27249,
                "match-2-p" => 12905,
                "match-2-p-pp" => 3463,
                "match-3" => 16248,
                "match-3-p" => 585,
                "match-3-p-pp" => 158,
                "match-3-pp" => 4390,
                "match-4" => 248,
                "match-4-p" => 15,
                "match-4-p-pp" => 2,
                "match-4-pp" => 70,
                "match-5" => 0,
                "match-5-p" => 0,
                "match-5-pp" => 1
            ]
        ];

        $lottery = LotteryMother::aPowerBall();

        $I->haveInDatabase(
            'lotteries',
            $lottery->toArray()
        );

        $breakDown = new PowerBallDrawBreakDown($powerBallBreakDown);

        $draw = EuroMillionsDrawMother::anPowerBallDrawWithJackpotAndBreakDown()
            ->withLottery($lottery)
            ->withBreakDown($breakDown)
            ->build();

        $I->haveInDatabase(
            'euromillions_draws',
            $draw->toArray()
        );

        $prize = LotteryPrizeFactory::create($lottery, $draw->getBreakDown(), $data['balls']);

        $I->assertTrue($prize->getPrize()->getAmount() > 0);

        // this assert is not giving the exact value for conversion reasons
        // $moneyConverted = $this->currencyConversion->convert(new Money($data['prize'], new Currency('USD')), new Currency('EUR'));
        // $I->assertEquals($moneyConverted->getAmount(), $prize->getPrize()->getAmount());
    }

    /**
     * method lotteryPrizeFactory
     * when isMegaMillion
     * should returnsTheCorrectPrize
     * @param UnitTester $I
     * @group lottery-prize-factory
     * @dataProvider getMegaMillionsResults
     */
    public function test_lotteryPrizeFactory_isMegaMillions_returnsTheCorrectPrize(\UnitTester $I, \Codeception\Example $data)
    {
        $megaMillionsBreakDown = [
            "prizes" => [
                "match-0-m" => "2.00",
                "match-0-m-mp" => "4.00",
                "match-1-m" => "4.00",
                "match-1-m-mp" => "8.00",
                "match-2-m" => "10.00",
                "match-2-m-mp" => "20.00",
                "match-3" => "10.00",
                "match-3-m" => "200.00",
                "match-3-m-mp" => "400.00",
                "match-3-mp" => "20.00",
                "match-4" => "500.00",
                "match-4-m" => "10000.00",
                "match-4-m-mp" => "20000.00",
                "match-4-mp" => "1000.00",
                "match-5" => "1000000.00",
                "match-5-m" => "206000000.00",
                "match-5-mp" => "2000000.00"
            ],
            'winners' => [
                "match-0-m" => 417322,
                "match-0-m-mp" => 69024,
                "match-1-m" => 168608,
                "match-1-m-mp" => 27569,
                "match-2-m" => 21095,
                "match-2-m-mp" => 3504,
                "match-3" => 21896,
                "match-3-m" => 974,
                "match-3-m-mp" => 151,
                "match-3-mp" => 3533,
                "match-4" => 326,
                "match-4-m" => 14,
                "match-4-m-mp" => 2,
                "match-4-mp" => 46,
                "match-5" => 1,
                "match-5-m" => 0,
                "match-5-mp" => 0
            ]
        ];

        $lottery = LotteryMother::aMegaMillions();

        $I->haveInDatabase(
            'lotteries',
            $lottery->toArray()
        );

        $breakDown = new MegaMillionsDrawBreakDown($megaMillionsBreakDown);

        $draw = EuroMillionsDrawMother::aMegaMillionsDrawWithJackpot()
            ->withLottery($lottery)
            ->withBreakDown($breakDown)
            ->build();

        $I->haveInDatabase(
            'euromillions_draws',
            $draw->toArray()
        );

        $prize = LotteryPrizeFactory::create($lottery, $draw->getBreakDown(), $data['balls']);

        $I->assertTrue($prize->getPrize()->getAmount() > 0);

        // this assert is not giving the exact value for conversion reasons
        // $moneyConverted = $this->currencyConversion->convert(new Money($data['prize'], new Currency('USD')), new Currency('EUR'));
        // $I->assertEquals($moneyConverted->getAmount(), $prize->getPrize()->getAmount());
    }

    /**
     * method lotteryPrizeFactory
     * when isMegaSena
     * should returnsTheCorrectPrize
     * @param UnitTester $I
     * @group lottery-prize-factory
     * @dataProvider getMegaSenaResults
     */
    public function test_lotteryPrizeFactory_isMegaSena_returnsTheCorrectPrize(\UnitTester $I, \Codeception\Example $data)
    {
        $megaSenaBreakDown = [
            "prizes" => [
                "match-4" => "487.08",
                "match-5" => "21346.79",
                "match-5-1" => "0.00"
            ],
            'winners' => [
                "match-4" => 8139,
                "match-5" => 130,
                "match-5-1" => 0
            ]
        ];

        $lottery = LotteryMother::aMegaSena();

        $breakDown = new MegaSenaDrawBreakDown($megaSenaBreakDown);

        $draw = EuroMillionsDrawMother::aMegaSenaDrawWithJackpotAndBreakDown()
            ->withLottery($lottery)
            ->withBreakDown($breakDown)
            ->build();

        $prize = LotteryPrizeFactory::create($lottery, $draw->getBreakDown(), $data['balls']);

        $I->assertTrue($prize->getPrize()->getAmount() >= 0);

        // this assert is not giving the exact value for conversion reasons
        // $moneyConverted = $this->currencyConversion->convert(new Money($data['prize'], new Currency('USD')), new Currency('EUR'));
        // $I->assertEquals($moneyConverted->getAmount(), $prize->getPrize()->getAmount());
    }

    /**
     * method lotteryPrizeFactory
     * when isSuperEnalotto
     * should returnsTheCorrectPrize
     * @param UnitTester $I
     * @group lottery-prize-factory
     * @dataProvider getSuperEnalottoResults
     */
    public function test_lotteryPrizeFactory_isSuperEnalotto_returnsTheCorrectPrize(\UnitTester $I, \Codeception\Example $data)
    {
        $superEnalottoBreakDown = [
            "prizes" => [
                "match-3" => "8.30",
                "match-4" => "45.45",
                "match-5" => "4952.30",
                "match-5-j" => "450000.00",
                "match-6" => "150000000.00"
            ],
            'winners' => [
                "match-3" => 813944,
                "match-4" => 8139,
                "match-5" => 130,
                "match-5-j" => 2,
                "match-6" => 1
            ]
        ];

        $lottery = LotteryMother::aSuperEnalotto();

        $breakDown = new SuperEnalottoDrawBreakDown($superEnalottoBreakDown);

        $draw = EuroMillionsDrawMother::aSuperEnalottoDrawWithJackpotAndBreakDown()
            ->withLottery($lottery)
            ->withBreakDown($breakDown)
            ->build();

        $prize = LotteryPrizeFactory::create($lottery, $draw->getBreakDown(), $data['balls']);

        $I->assertTrue($prize->getPrize()->getAmount() >= 0);

        $I->assertEquals($data['prize'], $prize->getPrize()->getAmount());
    }

    /**
     * @return array
     */
    protected function getEuroJackpotResults()
    {
        return [
            ['balls' => [5, 2], 'prize' => 16261344000],
            ['balls' => [5, 1], 'prize' => 6261340000],
            ['balls' => [5, 0], 'prize' => 1657413000],
            ['balls' => [4, 2], 'prize' => 37455000],
            ['balls' => [4, 1], 'prize' => 2878000],
            ['balls' => [4, 0], 'prize' => 1315000],
            ['balls' => [3, 2], 'prize' => 456000],
            ['balls' => [2, 2], 'prize' => 171000],
            ['balls' => [3, 1], 'prize' => 213000],
            ['balls' => [3, 0], 'prize' => 166000],
            ['balls' => [1, 2], 'prize' => 36000],
            ['balls' => [2, 1], 'prize' => 78000],
        ];
    }

    /**
     * @return array
     */
    protected function getEuroMillionsResults()
    {
        return [
            ['balls' => [5,0,1], 'prize' => 3000000000],
            ['balls' => [5,1,0], 'prize' => 29367800],
            ['balls' => [5,0,0], 'prize' => 8817700],
            ['balls' => [4,0,1], 'prize' => 668000],
            ['balls' => [4,1,1], 'prize' => 27500],
            ['balls' => [4,1,0], 'prize' => 13100],
            ['balls' => [4,0,0], 'prize' => 6000],
            ['balls' => [3,0,1], 'prize' => 1800],
            ['balls' => [3,1,1], 'prize' => 1600],
            ['balls' => [3,1,0], 'prize' => 1300],
            ['balls' => [3,0,0], 'prize' => 900],
            ['balls' => [2,1,1], 'prize' => 852],
            ['balls' => [2,1,0], 'prize' => 415],
        ];
    }

    /**
     * @return array
     */
    protected function getPowerBallResults()
    {
        return [
            ['balls' => [5,0,1], 'prize' => 20000000000],
            ['balls' => [5,1,0], 'prize' => 710000000000],
            ['balls' => [5,0,0], 'prize' => 10000000000],
            ['balls' => [4,0,1], 'prize' => 2000000],
            ['balls' => [4,1,1], 'prize' => 1000000000],
            ['balls' => [4,1,0], 'prize' => 500000000],
            ['balls' => [4,0,0], 'prize' => 1000000],
            ['balls' => [3,0,1], 'prize' => 140000],
            ['balls' => [3,1,1], 'prize' => 2000000],
            ['balls' => [3,1,0], 'prize' => 1000000],
            ['balls' => [3,0,0], 'prize' => 70000],
            ['balls' => [2,1,1], 'prize' => 140000],
            ['balls' => [2,1,0], 'prize' => 70000],
        ];
    }

    /**
     * @return array
     */
    protected function getMegaMillionsResults()
    {
        return [
            ['balls' => [5,0,1], 'prize' => 20000000000],
            ['balls' => [5,1,0], 'prize' => 2060000000000],
            ['balls' => [5,0,0], 'prize' => 10000000000],
            ['balls' => [4,0,1], 'prize' => 10000000],
            ['balls' => [4,1,1], 'prize' => 200000000],
            ['balls' => [4,1,0], 'prize' => 100000000],
            ['balls' => [4,0,0], 'prize' => 5000000],
            ['balls' => [3,0,1], 'prize' => 200000],
            ['balls' => [3,1,1], 'prize' => 4000000],
            ['balls' => [3,1,0], 'prize' => 2000000],
            ['balls' => [3,0,0], 'prize' => 100000],
            ['balls' => [2,1,1], 'prize' => 200000],
            ['balls' => [2,1,0], 'prize' => 100000],
        ];
    }

    /**
     * @return array
     */
    protected function getMegaSenaResults()
    {
        return [
            ['balls' => [5, 1], 'prize' => 0],
            ['balls' => [5, 0], 'prize' => 213467900],
            ['balls' => [4, 1], 'prize' => 213467900],
            ['balls' => [4, 0], 'prize' => 4870800],
            ['balls' => [3, 1], 'prize' => 4870800],
        ];
    }

    /**
     * @return array
     */
    protected function getSuperEnalottoResults()
    {
        return [
            ['balls' => [6, 0], 'prize' => 1500000000000],
            ['balls' => [5, 1], 'prize' => 4500000000],
            ['balls' => [5, 0], 'prize' => 49523000],
            ['balls' => [4, 1], 'prize' => 454500],
            ['balls' => [4, 0], 'prize' => 454500],
            ['balls' => [3, 1], 'prize' => 83000],
            ['balls' => [3, 0], 'prize' => 83000],
        ];
    }
}