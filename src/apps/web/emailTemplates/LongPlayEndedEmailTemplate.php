<?php


namespace EuroMillions\web\emailTemplates;


class LongPlayEndedEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars()
    {
        $jackpot = $this->lotteriesDataService->getNextJackpot('EuroMillions');

        //vars email template
        $vars = [
            'template' => 'long-play-is-ended',
            'subject' => 'Your long play is ended',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => $jackpot->getAmount() /100
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => $this->config->domain['url'] . 'play'
                    ]
                ]
        ];

        return $vars;
    }
}