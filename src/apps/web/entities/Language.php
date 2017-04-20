<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class Language extends EntityBase implements IEntity
{
    protected $id;
    protected $ccode;
    protected $active;
    protected $defaultLocale;

    /**
     * Language constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setCcode($data['ccode']);
        $this->setDefaultLocale($data['defaultLocale']);
        $this->setActive($data['active']);
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

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

}