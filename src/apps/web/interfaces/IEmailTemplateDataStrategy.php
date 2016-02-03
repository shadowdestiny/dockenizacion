<?php


namespace EuroMillions\web\interfaces;


interface IEmailTemplateDataStrategy
{
    public function getData(IEmailTemplateDataStrategy $strategy = null);
}