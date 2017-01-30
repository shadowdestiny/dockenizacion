<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class TrackingCodes extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $description;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setName($data['name']);
        $this->setDescription($data['description']);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
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
    public function setDescription($description)
    {
        $this->description = $description;
    }
}