<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\dto\PastDrawDTO;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Money\Currency;
use Money\Money;

class PastDrawDTOUnitTest extends UnitTestBase
{


    /**
     * method __construct
     * when called
     * should createObjectWithFillProperties
     */
    public function test___construct_called_createObjectWithFillProperties()
    {
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $bet->setMatchNumbers('1,2');
        $bet->setMatchStars('1');
        $bet->setPrize(new Money((int) 10000, new Currency('EUR')));
        $result = [
            0 => $bet,
           'line.regular_number_one' => 1,
            'line.regular_number_two' => 2,
            'line.regular_number_three' => 3,
            'line.regular_number_four' => 4,
            'line.regular_number_five' => 5,
            'line.lucky_number_one' => 1,
            'line.lucky_number_two' => 2,
        ];

        $actual = new PastDrawDTO($result);
        $this->assertEquals('100',$actual->prize);
        $this->assertEquals(['1'=>2,'2'=>2,'3'=>1,'4'=>1,'5'=>1],$actual->numbers);
        $this->assertEquals(['1'=>2,'2'=>1],$actual->stars);

    }

    private function getPlayConfigAndEuroMillionsDraw()
    {
        $user = $this->getUser();
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
                'line' => $euroMillionsLine
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

    /**
     * @param string $currency
     * @return User
     */
    private function getUser()
    {
        return UserMother::aUserWith500Eur()->build();
    }

    
   
}