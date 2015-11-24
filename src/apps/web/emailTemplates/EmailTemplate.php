<?php


namespace EuroMillions\web\emailTemplates;

class EmailTemplate implements IEmailTemplate
{

    protected $vars;

    public function loadVars()
    {
        $date = new \DateTime();

        $vars = [
            'date' => $date->format('j M Y')
        ];

        return $vars;
    }
}