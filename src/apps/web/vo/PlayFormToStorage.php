<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\forms\EMForm;


class PlayFormToStorage extends EMForm
{

    public $drawDays;

    public $startDrawDate;

    public $lastDrawDate;

    public $frequency;

    public $amount;

    public $regular_numbers;

    public $lucky_numbers;

    public $euroMillionsLines;

    public $numbers;

    public function toJson()
    {

        /** @var EuroMillionsLine[] $arr_lines */
        $arr_lines = $this->euroMillionsLines;
        $this->euroMillionsLines = null;
        foreach($arr_lines as $lines){
            $this->euroMillionsLines[] = [
                                            'regular' => $lines->getRegularNumbersArray(),
                                            'lucky'   => $lines->getLuckyNumbersArray()
            ];
        }
        return json_encode(get_object_vars($this));
    }

    public static function className()
    {
        return get_class();
    }

}