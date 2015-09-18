<?php


namespace EuroMillions\vo;


class PlayFormToStorage
{

    public $drawDays;

    public $startDrawDate;

    public $lastDrawDate;

    public $frequency;

    public $amount;

    public $euroMillionsLine;

    public function toJson()
    {
        return json_encode(get_object_vars($this));
    }
}