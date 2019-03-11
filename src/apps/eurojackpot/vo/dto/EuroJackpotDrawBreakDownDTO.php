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

    public $category_one;

    public $category_two;

    public $category_three;

    public $category_four;

    public $category_five;

    public $category_six;

    public $category_seven;

    public $category_eight;

    public $category_nine;

    public $category_ten;

    public $category_eleven;

    public $category_twelve;

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
        return json_decode(json_encode($this),TRUE);
    }

    public function toJson()
    {
        return json_encode(json_decode(json_encode($this),TRUE));
    }

    private function setDataToLine()
    {
        return [
            'category_one' => [
                'name' => 'match-5-2',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getWinners(),
                'numbers_corrected' => '5',
                'stars_corrected' => '2',
            ],
            'category_two' => [
                'name' => 'match-5-1',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getWinners(),
                'numbers_corrected' => '5',
                'stars_corrected' => '1',
            ],
            'category_three' => [
                'name' => 'match-5',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getWinners(),
                'numbers_corrected' => '5',
                'stars_corrected' => '0',
            ],
            'category_four' => [
                'name' => 'match-4-2',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryFour()->getWinners(),
                'numbers_corrected' => '4',
                'stars_corrected' => '2',
            ],
            'category_five' => [
                'name' => 'match-4-1',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryFive()->getWinners(),
                'numbers_corrected' => '4',
                'stars_corrected' => '1',
            ],
            'category_six' => [
                'name' => 'match-4',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategorySix()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategorySix()->getWinners(),
                'numbers_corrected' => '4',
                'stars_corrected' => '0',
            ],
            'category_seven' => [
                'name' => 'match-3-2',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategorySeven()->getWinners(),
                'numbers_corrected' => '3',
                'stars_corrected' => '2',
            ],
            'category_eight' => [
                'name' => 'match-2-2',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryTen()->getWinners(),
                'numbers_corrected' => '2',
                'stars_corrected' => '2',
            ],
            'category_nine' => [
                'name' => 'match-3-1',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryEight()->getWinners(),
                'numbers_corrected' => '3',
                'stars_corrected' => '1',
            ],
            'category_ten' => [
                'name' => 'match-3',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryNine()->getWinners(),
                'numbers_corrected' => '3',
                'stars_corrected' => '0',
            ],
            'category_eleven' => [
                'name' => 'match-1-2',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryEleven()->getWinners(),
                'numbers_corrected' => '1',
                'stars_corrected' => '2',
            ],
            'category_twelve' => [
                'name' => 'match-2-1',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryTwelve()->getWinners(),
                'numbers_corrected' => '2',
                'stars_corrected' => '1',
            ],
        ];

    }

}