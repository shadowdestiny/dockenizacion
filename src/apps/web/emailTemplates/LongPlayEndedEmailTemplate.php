<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;

class LongPlayEndedEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {

        $strategy = $strategy ? $strategy : new JackpotDataEmailTemplateStrategy();
        $data = $this->emailTemplateDataStrategy->getData($strategy);

        $vars = [
            'template' => 'long-play-is-ended',
            'subject' => 'Your play has ended',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => number_format((float) $data['jackpot_amount']->getAmount() / 100,2,".",",")
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => $this->config . '/play'
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