<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;

class NullEmailTemplateDataStrategy implements IEmailTemplateDataStrategy
{

    public function __construct()
    {

    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        return null;
    }
}