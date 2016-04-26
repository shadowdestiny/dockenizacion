<?php


namespace EuroMillions\web\vo;


class CastilloBetId
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public static function create()
    {
        return new static(CastilloBetId::generateBetId());
    }

    private static function generateBetId()
    {
        $micrtime = explode(" ",microtime());
        return substr(date("YmdHis").substr($micrtime[0],2),0,20);
    }

    public function __toString()
    {
        return $this->id;
    }
}