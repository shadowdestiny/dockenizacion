<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:35
 */

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use ReflectionClass;

class PowerBallDrawBreakDownDTO extends EuroMillionsDrawBreakDownDTO implements IDto
{


    public $lineOne;

    public $lineTwo;

    public $lineThree;

    public $lineFour;

    public $lineFive;

    public $lineSix;

    public $lineSeven;

    public $lineEight;

    public $lineNine;

    public $category_fourteen;

    public $category_fifteen;

    public $category_sixteen;

    public $category_seventeen;



    public function __construct(EuroMillionsDrawBreakDown $euroMillionsDrawBreakDown)
    {
        $this->euroMillionsDrawBreakDown = $euroMillionsDrawBreakDown;
        $this->exChangeObject();
    }


    public function exChangeObject()
    {
        foreach ($this->setDataToLine() as $k => $data)
        {
            $this->$k = new PowerBallBreakDownDataDTO($data);
        }
    }

    public function toArray()
    {
        unset($this->{"category_fourteen"});
        unset($this->{"category_fourteen"});
        unset($this->{"category_fifteen"});
        unset($this->{"category_sixteen"});
        unset($this->{"category_seventeen"});
        unset($this->{"category_ten"});
        unset($this->{"category_eleven"});
        unset($this->{"category_nine"});
        unset($this->{"category_eight"});
        unset($this->{"category_thirteen"});
        unset($this->{"category_twelve"});
        unset($this->{"category_seven"});
        unset($this->{"category_six"});
        unset($this->{"category_five"});
        unset($this->{"category_four"});
        unset($this->{"category_three"});
        unset($this->{"category_two"});
        unset($this->{"category_one"});
        return json_decode(json_encode($this),TRUE);
    }

    public function toJson()
    {
        return json_encode(json_decode(json_encode($this),TRUE));
    }

    private function setDataToLine()
    {
        return [
            'lineOne' => [
                'name' => 5,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => 0,
                'powerPlayPrize' => 0,
                'showPowerball' => true
            ],
            'lineTwo' => [
                'name' => 5,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getLotteryPrize()->getAmount(),
                'showPowerball' => false
            ],
            'lineThree' => [
                'name' => 4,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategorySix()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategorySix()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getLotteryPrize()->getAmount(),
                'showPowerball' => true
            ],
            'lineFour' => [
                'name' => 4,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getLotteryPrize()->getAmount(),
                'showPowerball' => false
            ],
            'lineFive' => [
                'name' => 3,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getLotteryPrize()->getAmount(),
                'showPowerball' => true
            ],
            'lineSix' => [
                'name' => 3,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getLotteryPrize()->getAmount(),
                'showPowerball' => false
            ],
            'lineSeven' => [
                'name' => 2,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategoryThirteen()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategoryThirteen()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getLotteryPrize()->getAmount(),
                'showPowerball' => true
            ],
            'lineEight' => [
                'name' => 1,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategoryFifteen()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategoryFifteen()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategoryFourteen()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategoryFourteen()->getLotteryPrize()->getAmount(),
                'showPowerball' => true
            ],
            'lineNine' => [
                'name' => 0,
                'winnersPowerBall' => $this->euroMillionsDrawBreakDown->getCategorySeventeen()->getWinners(),
                'powerBallPrize' => $this->euroMillionsDrawBreakDown->getCategorySeventeen()->getLotteryPrize()->getAmount(),
                'winnersPowerPlay' => $this->euroMillionsDrawBreakDown->getCategorySixteen()->getWinners(),
                'powerPlayPrize' => $this->euroMillionsDrawBreakDown->getCategorySixteen()->getLotteryPrize()->getAmount(),
                'showPowerball' => true
            ]
        ];

    }

}