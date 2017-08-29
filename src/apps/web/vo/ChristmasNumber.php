<?php

namespace EuroMillions\web\vo;

use EuroMillions\shared\interfaces\IArraySerializable;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ChristmasNumber
{
    protected $number;
    protected $serie;
    protected $fraccion;


    /**
     * @param EuroMillionsRegularNumber[] $regular_numbers
     * @param EuroMillionsLuckyNumber[] $lucky_numbers
     */
    public function __construct($number, $serie, $fraccion)
    {
        $this->number = $number;
        $this->serie = $serie;
        $this->fraccion = $fraccion;
    }

    /**
     * @return mixed
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param mixed $serie
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
    }

    /**
     * @return mixed
     */
    public function getFraccion()
    {
        return $this->fraccion;
    }

    /**
     * @param mixed $fraccion
     */
    public function setFraccion($fraccion)
    {
        $this->fraccion = $fraccion;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /** @return string */
    public function __toString()
    {
        $properties = [];
        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);
        foreach ($props as $prop) {
            $property_name = $reflect->getProperty($prop->getName());
            $property_name->setAccessible(true);
            $properties[$property_name->getName()] = $property_name->getValue($this);
        }
        return $properties;
    }
}