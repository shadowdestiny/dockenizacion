<?php


namespace EuroMillions\web\emailTemplates;

class EmailTemplate implements IEmailTemplate
{

    public function loadVars()
    {

    }

    public function loadHeader()
    {
        $date = new \DateTime();
        return [
            'name' => 'date_header',
            'content' => $date->format('j M Y')
        ];
    }

    public function loadFooter()
    {
        $config = \Phalcon\Di::getDefault()->get('config');
        return [
            'name' => 'redirect_unsubscribe',
            'content' => $config->domain['url'] . '/unsubscribe'
        ];
    }


    public function loadVarsAsObject(array $vars = null)
    {

    }
}