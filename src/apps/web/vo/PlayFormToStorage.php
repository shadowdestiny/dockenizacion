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

    public $regular_numnbers;

    public $lucky_numbers;

    /** @var EuroMillionsLine $euroMillionsLine */
    public $euroMillionsLines;


    public function toJson()
    {

        foreach($this->euroMillionsLines as $numbers) {
            $this->regular_numnbers[] = ['numbers' => $numbers->getRegularNumbers(),
                                         'lucky'   => $numbers->getLuckyNumbers()
                                        ];
        }
        return json_encode(get_object_vars($this));
    }

    public static function className()
    {
        return get_class();
    }

}