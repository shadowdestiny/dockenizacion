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
     * @return mixed
     */
    public function getTrackingCode()
    {
        return $this->trackingCodeId;
    }

    /**
     * @param mixed $trackingCodeId
     */
    public function setTrackingCode($trackingCodeId)
    {
        $this->trackingCodeId = $trackingCodeId;
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