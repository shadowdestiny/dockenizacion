<?php
namespace app\entities;

class Language
{
    protected $id;
    protected $ccode;

    public function getId()
    {
        return $this->id;
    }

    public function getCcode()
    {
        return $this->ccode;
    }

    public function setCcode($ccode)
    {
        $this->ccode = $ccode;
    }
}