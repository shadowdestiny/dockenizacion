<?php

namespace EuroMillions\web\components;


use EuroMillions\web\interfaces\IPdfTemplate;

abstract class PdfTemplateDecorator implements IPdfTemplate
{

    protected $pdfTemplate;


    public function __construct(IPdfTemplate $pdfTemplate)
    {
        $this->pdfTemplate = $pdfTemplate;
    }

    abstract public function loadBody();

    abstract public function loadHeader();

    abstract public function loadFooter();


}