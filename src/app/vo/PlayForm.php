<?php


namespace EuroMillions\vo;


class PlayForm
{

    protected $euroMillionsLines;

    public function __construct(array $euroMillionsLines)
    {
        $this->euroMillionsLines = $euroMillionsLines;
    }

    public function getEuroMillionsLines()
    {
        return $this->euroMillionsLines;
    }

}