<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use Doctrine\Common\Collections\ArrayCollection;

class Translation implements IEntity
{
    protected $id;
    protected $key;
    protected $used;
    protected $translatedTo;

    public function __construct()
    {
        $this->translatedTo = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key= $key;
    }

    public function getUsed()
    {
        return $this->used;
    }

    public function setUsed($used)
    {
        $this->used = $used;
    }
}