<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class TrackingCodes extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $description;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setLeftEdge($description)
    {
        $this->description = $description;
    }
}