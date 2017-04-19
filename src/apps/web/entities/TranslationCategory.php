<?php

namespace EuroMillions\web\entities;

use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\web\interfaces\IEntity;

class TranslationCategory implements IEntity
{

    protected $id;
    protected $categoryName;
    protected $categoryCode;
    protected $description;
    protected $translation;

    /**
     * TranslationCategory constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->translation = new ArrayCollection();
        $this->setCategoryName($data['name']);
        $this->setCategoryCode($data['code']);
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
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param mixed $categoryName
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return mixed
     */
    public function getCategoryCode()
    {
        return $this->categoryCode;
    }

    /**
     * @param mixed $categoryCode
     */
    public function setCategoryCode($categoryCode)
    {
        $this->categoryCode = $categoryCode;
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