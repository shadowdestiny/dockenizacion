<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class TcActions extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $conditions;
    protected $trackingCode;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setName($data['name']);
        $this->setConditions($data['conditions']);
        $this->setTrackingCode($data['trackingCodeId']);
    }

    /**
     * @return mixed
     */
    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    /**
     * @param mixed $trackingCode
     */
    public function setTrackingCode($trackingCode)
    {
        $this->trackingCode = $trackingCode;
    }

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
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param string $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }
}