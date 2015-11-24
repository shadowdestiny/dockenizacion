<?php


namespace EuroMillions\web\emailTemplates;

class EmailTemplate implements IEmailTemplate
{


    //EMTD add footer, for example, unsubscribe link

    public function loadVars()
    {
        $date = new \DateTime();
        $vars = [
            'date' => $date->format('j M Y')
        ];

        return $vars;
    }
}