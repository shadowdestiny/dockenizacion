<?php

namespace EuroMillions\entities;

use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\interfaces\IEntity;

class Lottery implements IEntity
{
    protected $id;
    protected $name;
    protected $active;
    protected $draws;

    public function __construct()
    {
        $this->draws = new ArrayCollection();
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}