<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:35
 */

namespace EuroMillions\eurojackpot\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use ReflectionClass;

class EuroJackpotDrawBreakDownDTO extends EuroMillionsDrawBreakDownDTO implements IDto
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
                'winnersEuroJackpot' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getWinners(),
                'euroJackpotPrize' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getLotteryPrize()->getAmount(),
                'showEuroJackpot' => true
            ],
            'lineTwo' => [
                'name' => 5,
                'winnersEuroJackpot' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getWinners(),
                'euroJackpotPrize' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getLotteryPrize()->getAmount(),
                'showEuroJackpot' => false
            ],
            'lineThree' => [
                'name' => 4,
                'winnersEuroJackpot' => $this->euroMillionsDrawBreakDown->getCategorySix()->getWinners(),
                'euroJackpotPrize' => $this->euroMillionsDrawBreakDown->getCategorySix()->getLotteryPrize()->getAmount(),
                'showEuroJackpot' => true
            ],
            'lineFour' => [
                'name' => 3,
                'winnersEuroJackpot' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getWinners(),
                'euroJackpotPrize' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getLotteryPrize()->getAmount(),
                'showEuroJackpot' => false
            ],
            'lineFive' => [
                'name' => 2,
                'winnersEuroJackpot' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getWinners(),
                'euroJackpotPrize' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getLotteryPrize()->getAmount(),
                'showEuroJackpot' => true
            ],
            'lineSix' => [
                'name' => 2,
                'winnersEuroJackpot' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getWinners(),
                'euroJackpotPrize' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getLotteryPrize()->getAmount(),
                'showEuroJackpot' => false
            ],
        ];

    }

}