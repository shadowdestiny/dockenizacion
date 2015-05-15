<?php
namespace app\entities;

use app\interfaces\IEntity;
use Doctrine\Common\Collections\ArrayCollection;

class Language implements IEntity
{
    protected $id;
    protected $ccode;
    protected $usedIn;
    protected $active;

    public function __construct()
    {
        $this->usedIn = new ArrayCollection();
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
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