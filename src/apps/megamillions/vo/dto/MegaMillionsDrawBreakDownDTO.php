<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:35
 */

namespace EuroMillions\megamillions\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use ReflectionClass;

class MegaMillionsDrawBreakDownDTO extends EuroMillionsDrawBreakDownDTO implements IDto
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
            $this->$k = new MegaMillionsBreakDownDataDTO($data);
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
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => 0,
                'megaplierPrize' => 0,
                'showMegaMillions' => true
            ],
            'lineTwo' => [
                'name' => 5,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => false
            ],
            'lineThree' => [
                'name' => 4,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategorySix()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategorySix()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => true
            ],
            'lineFour' => [
                'name' => 4,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => false
            ],
            'lineFive' => [
                'name' => 3,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => true
            ],
            'lineSix' => [
                'name' => 3,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => false
            ],
            'lineSeven' => [
                'name' => 2,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategoryThirteen()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategoryThirteen()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => true
            ],
            'lineEight' => [
                'name' => 1,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategoryFifteen()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategoryFifteen()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategoryFourteen()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategoryFourteen()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => true
            ],
            'lineNine' => [
                'name' => 0,
                'winnersMegaMillions' => $this->euroMillionsDrawBreakDown->getCategorySeventeen()->getWinners(),
                'megaMillionsPrize' => $this->euroMillionsDrawBreakDown->getCategorySeventeen()->getLotteryPrize()->getAmount(),
                'winnersMegaplier' => $this->euroMillionsDrawBreakDown->getCategorySixteen()->getWinners(),
                'megaplierPrize' => $this->euroMillionsDrawBreakDown->getCategorySixteen()->getLotteryPrize()->getAmount(),
                'showMegaMillions' => true
            ]
        ];

    }

}