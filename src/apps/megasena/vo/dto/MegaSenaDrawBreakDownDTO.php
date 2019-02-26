<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 26/02/19
 * Time: 13:26
 */

namespace EuroMillions\megasena\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use ReflectionClass;

class MegaSenaDrawBreakDownDTO extends EuroMillionsDrawBreakDownDTO implements IDto
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
            $this->$k = new EuroJackpotBreakDownDataDTO($data);
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
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getLotteryPrize()->getAmount(),
                'showMegaSena' => true
            ],
            'lineTwo' => [
                'name' => 5,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
            'lineThree' => [
                'name' => 5,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
            'lineFour' => [
                'name' => 4,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getLotteryPrize()->getAmount(),
                'showMegaSena' => true
            ],
            'lineFive' => [
                'name' => 4,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getLotteryPrize()->getAmount(),
                'showMegaSena' => true
            ],
            'lineSix' => [
                'name' => 4,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategorySix()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategorySix()->getLotteryPrize()->getAmount(),
                'showMegaSena' => true
            ],
            'lineSeven' => [
                'name' => 3,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
            'lineEight' => [
                'name' => 3,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getLotteryPrize()->getAmount(),
                'showMegaSena' => true
            ],
            'lineNine' => [
                'name' => 3,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
            'lineTen' => [
                'name' => 2,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
            'lineEleven' => [
                'name' => 2,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
            'lineTwelve' => [
                'name' => 1,
                'winnersMegaSena' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getWinners(),
                'megaSenaPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getLotteryPrize()->getAmount(),
                'showMegaSena' => false
            ],
        ];

    }

}