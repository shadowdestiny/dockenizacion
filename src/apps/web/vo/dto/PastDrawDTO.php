<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class PastDrawDTO extends DTOBase implements IDto
{

    public $numbers;
    public $stars;
    public $prize;
    private $pastDraw;

    public function __construct($pastDraw)
    {
        $this->pastDraw = $pastDraw;
        $this->createLines();
    }

    public function exChangeObject()
    {

    }

    public function toArray()
    {

    }

    private function createLines()
    {
        $regularNumbers = [
                        $this->pastDraw['line.regular_number_one'],
                        $this->pastDraw['line.regular_number_two'],
                        $this->pastDraw['line.regular_number_three'],
                        $this->pastDraw['line.regular_number_four'],
                        $this->pastDraw['line.regular_number_five'],
        ];
        $starNumbers = [
                        $this->pastDraw['line.lucky_number_one'],
                        $this->pastDraw['line.lucky_number_two'],
        ];
        $matchRegularNumbers = !empty($this->pastDraw[0]->getMatchNumbers()) ? explode(',',$this->pastDraw[0]->getMatchNumbers()) : [];
        $matchStarNumbers = !empty($this->pastDraw[0]->getMatchStars()) ? explode(',',$this->pastDraw[0]->getMatchStars()) : [];
        $this->numbers = array_count_values(array_merge($regularNumbers,$matchRegularNumbers));
        $this->stars = array_count_values(array_merge($starNumbers,$matchStarNumbers));
        $this->prize = !empty($this->pastDraw[0]->getPrize()) ? $this->pastDraw[0]->getPrize()->getAmount() / 100 : 0;
    }
}