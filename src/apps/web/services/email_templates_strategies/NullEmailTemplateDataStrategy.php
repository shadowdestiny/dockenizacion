<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\EmailTemplateDataStrategy;

class NullEmailTemplateDataStrategy implements EmailTemplateDataStrategy
{

    public function __construct()
    {

    }

    public function getData()
    {
        return null;
    }
}