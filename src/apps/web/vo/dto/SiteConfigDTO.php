<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\SiteConfig;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class SiteConfigDTO extends DTOBase implements IDto
{

    public $name;

    public $value;

    public $description;

    protected $siteConfig;

    public function __construct(SiteConfig $siteConfig)
    {
        $this->siteConfig = $siteConfig;
        $this->exChangeObject();
    }


    public function toArray()
    {

    }

    public function exChangeObject()
    {
        $this->name = $this->siteConfig->getName();
        $this->value = $this->siteConfig->getValue();
        $this->description = $this->siteConfig->getDescription();
    }
}