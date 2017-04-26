<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class TranslationDetail implements IEntity
{
    protected $id;
    protected $value;
    protected $translation;
    protected $lang;
    protected $language;

    /**
     * TranslationDetail constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setTranslation($data['translationId']);
        $this->setLanguage($data['languageId']);
        $this->setLang($data['lang']);
        $this->setValue($data['value']);
    }

    public function getTranslation()
    {
        return $this->translation;
    }

    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

}