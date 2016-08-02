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


    public function __construct()
    {
        $this->translation = new ArrayCollection();
    }


    public function getId()
    {
        // TODO: Implement getId() method.
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