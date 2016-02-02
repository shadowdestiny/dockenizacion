<?php


namespace EuroMillions\web\emailTemplates;


class LongPlayEndedEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $vars = [
            'template' => 'long-play-is-ended',
            'subject' => 'Your long play is ended',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => number_format((float) $data['jackpot_amount']->getAmount() / 100,2,".",",")
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => $this->config->domain['url'] . 'play'
                    ]
                ]
        ];

        return $vars;
    }

    public function loadHeader()
    {
        return $this->emailTemplate->loadHeader();
    }

    public function loadFooter()
    {
        return $this->emailTemplate->loadFooter();
    }
}