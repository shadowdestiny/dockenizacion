<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use Doctrine\Common\Collections\ArrayCollection;

class Translation implements IEntity
{
    protected $id;
    protected $translationKey;
    protected $used;
    protected $translatedTo;
    protected $description;
    protected $translationCategory;

    public function __construct(array $data)
    {
        $this->translatedTo = new ArrayCollection();
        $this->setKey($data['key']);
        $this->setUsed(0);
        $this->setDescription($data['description']);
        $this->setTranslationCategory($data['categoryId']);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->translationKey;
    }

    public function setKey($translationKey)
    {
        $this->translationKey= $translationKey;
    }

    public function getUsed()
    {
        return $this->used;
    }

    public function setUsed($used)
    {
        $this->used = $used;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getTranslationCategory()
    {
        return $this->translationCategory;
    }

    public function setTranslationCategory($translationCategory)
    {
        $this->translationCategory = $translationCategory;
    }
}