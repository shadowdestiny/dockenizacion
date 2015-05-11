<?php
namespace app\entities;

use Doctrine\Common\Collections\ArrayCollection;

class Language
{
    protected $id;
    protected $ccode;
    protected $usedIn;

    public function __construct()
    {
        $this->usedIn = new ArrayCollection();
    }

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