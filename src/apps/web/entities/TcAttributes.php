<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class TcAttributes extends EntityBase implements IEntity
{
    protected $id;
    protected $name;
    protected $conditions;
    protected $functionName;
    protected $relationshipTable;
    protected $trackingCode;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setName($data['name']);
        $this->setConditions($data['conditions']);
        $this->setFunctionName($data['functionName']);
        $this->setRelationshipTable($data['relationshipTable']);
        $this->setTrackingCode($data['trackingCodeId']);
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

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }

    /**
     * @param string $functionName
     */
    public function setFunctionName($functionName)
    {
        $this->functionName = $functionName;
    }

    /**
     * @return string
     */
    public function getRelationshipTable()
    {
        return $this->relationshipTable;
    }

    /**
     * @param string $relationshipTable
     */
    public function setRelationshipTable($relationshipTable)
    {
        $this->relationshipTable = $relationshipTable;
    }

    /**
     * @return int
     */
    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    /**
     * @param int $trackingCode
     */
    public function setTrackingCode($trackingCode)
    {
        $this->trackingCode = $trackingCode;
    }
}