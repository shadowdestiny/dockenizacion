<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class SiteConfig extends EntityBase implements IEntity
{

    protected $id;

    protected $name;

    protected $value;

    protected $description;


    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}