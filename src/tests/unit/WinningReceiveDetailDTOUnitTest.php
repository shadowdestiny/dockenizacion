<?php


namespace tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\dto\WinningReceiveDetailDTO;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Money\Currency;
use Money\Money;

class WinningReceiveDetailDTOUnitTest extends UnitTestBase
{


    /**
     * method __construct
     * when called
     * should createProperDTO
     */
    public function test___construct_called_createProperDTO()
    {
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $bet->setMatchNumbers('2,4');
        $bet->setMatchStars('5');
        $actual = new WinningReceiveDetailDTO($euroMillionsDraw,$bet);
        $this->assertEquals('2,4',$actual->matchNumbers);
        $this->assertEquals('1,2,3,4,5',$actual->regularNumbers);
        $this->assertEquals('5,8',$actual->luckyNumbers);
    }

    private function getPlayConfigAndEuroMillionsDraw()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'freq',
            'draw_time' => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $euroMillionsDraw->setLottery($lottery);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euroMillionsLine,
                'startDrawDate' => new \DateTime()
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }

    protected function getRegularNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    protected function getLuckyNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }



}