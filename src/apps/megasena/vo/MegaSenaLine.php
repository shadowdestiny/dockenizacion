<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 14/02/19
 * Time: 04:55 PM
 */

namespace EuroMillions\megasena\vo;

use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsResultNumber;


class MegaSenaLine extends EuroMillionsLine
{
    const NUM_REGULAR_NUMBERS = 6;

    private $regular_numbers;

    protected $regular_number_one;
    protected $regular_number_two;
    protected $regular_number_three;
    protected $regular_number_four;
    protected $regular_number_five;
    protected $regular_number_six;

    protected $lucky_number_one;
    protected $lucky_number_two;


    /**
     * @param EuroMillionsRegularNumber[] $regular_numbers
     * @param $lottery
     */
    public function __construct(array $regular_numbers, $lottery = null)
    {

        if ($lottery == null) {
            if ($this->checkTypeAndRepeated($regular_numbers, 'EuroMillionsRegularNumber')) {
                throw new \InvalidArgumentException("The result numbers cannot have duplicates");
            }
        }

        $callback = function (EuroMillionsResultNumber $elem) {
            return $elem->getNumber();
        };

        if(empty($regular_numbers)) {
            $this->regular_numbers = [];
        } else {
            $this->regular_numbers = implode(',',array_map($callback, $regular_numbers));
            $this->setPropertiesValues($regular_numbers, $lottery);
        }
    }

    private function setPropertiesValues(array $regular_numbers, $lottery = null)
    {
        if (!$lottery) {
            sort($regular_numbers);
        }

        $this->regular_number_one   = $regular_numbers[0]->getNumber();
        $this->regular_number_two   = $regular_numbers[1]->getNumber();
        $this->regular_number_three = $regular_numbers[2]->getNumber();
        $this->regular_number_four  = $regular_numbers[3]->getNumber();
        $this->regular_number_five  = $regular_numbers[4]->getNumber();
        $this->regular_number_six   = $regular_numbers[5]->getNumber();

        $this->lucky_number_one = 0;
        $this->lucky_number_two = $this->regular_number_six;

    }

    public function toJsonData()
    {
        return [
            'regular'   => $this->getRegularNumbersArray(),
            'lucky'     => $this->getLuckyNumbersArray()
        ];
    }

}