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

    public $euroMillionsLines;

    public function toJson()
    {
        return json_encode(get_object_vars($this));
    }

    public static function className()
    {
        return get_class();
    }

}