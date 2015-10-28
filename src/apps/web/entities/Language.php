<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use Doctrine\Common\Collections\ArrayCollection;

class Language extends EntityBase implements IEntity
{
    protected $id;
    protected $ccode;
    protected $active;
    protected $defaultLocale;

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

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

}