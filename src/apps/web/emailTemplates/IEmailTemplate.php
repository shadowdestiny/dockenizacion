<?php


namespace EuroMillions\web\emailTemplates;


interface IEmailTemplate
{
    public function loadHeader();
    public function loadVars();
    public function loadFooter();
}