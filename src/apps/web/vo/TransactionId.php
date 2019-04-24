<?php


namespace EuroMillions\web\vo;


class TransactionId
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
        $id = str_pad(mt_rand(0, 999999999), 6, '0', STR_PAD_LEFT);
        return new static($id);
    }

    public function __toString()
    {
        return $this->id;
    }



}