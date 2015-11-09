<?php


namespace EuroMillions\web\vo;

class CastilloTicketId
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
        $id_number = substr(number_format(time() * mt_rand(),0,'',''),0,10);
        return new static($id_number);
    }

    public function __toString()
    {
        return $this->id;
    }




}