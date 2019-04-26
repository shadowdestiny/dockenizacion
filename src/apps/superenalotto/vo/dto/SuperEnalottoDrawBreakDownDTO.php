<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:35
 */

namespace EuroMillions\superenalotto\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use ReflectionClass;

class SuperEnalottoDrawBreakDownDTO extends EuroMillionsDrawBreakDownDTO implements IDto
{

    public $category_one;

    public $category_two;

    public $category_three;


    public function __construct(EuroMillionsDrawBreakDown $euroMillionsDrawBreakDown)
    {
        $this->euroMillionsDrawBreakDown = $euroMillionsDrawBreakDown;
        $this->exChangeObject();
    }


    public function exChangeObject()
    {
        foreach ($this->setDataToLine() as $k => $data)
        {
            $this->$k = new SuperEnalottoBreakDownDataDTO($data);
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
                'name' => 'match-5-1',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryOne()->getWinners(),
                'numbers_corrected' => '5',
                'stars_corrected' => '1',
            ],
            'category_two' => [
                'name' => 'match-5',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryTwo()->getWinners(),
                'numbers_corrected' => '5',
                'stars_corrected' => '0',
            ],
            'category_three' => [
                'name' => 'match-4',
                'lottery_prize' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getLotteryPrize()->getAmount(),
                'winners' => $this->euroMillionsDrawBreakDown->getCategoryThree()->getWinners(),
                'numbers_corrected' => '4',
                'stars_corrected' => '0',
            ],
        ];
    }

}