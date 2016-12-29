<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;

class LowBalanceEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {
        $strategy = $strategy ? $strategy : new JackpotDataEmailTemplateStrategy();
        $data = $this->emailTemplateDataStrategy->getData($strategy);

        $jackpot = $data['jackpot_amount'];
        $draw_day_format_one = $data['draw_day_format_one'];
        $draw_day_format_two = $data['draw_day_format_two'];

        $vars = [
            'template' => '1188463',
            'subject' => 'Low balance',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => $jackpot
                    ],
                    [
                        'name'    => 'draw_day_format_one',
                        'content' => $draw_day_format_one
                    ],
                    [
                        'name'    => 'draw_day_format_two',
                        'content' => $draw_day_format_two,
                    ],
                    [
                        'name' => 'url_add_funds',
                        'content' => $this->config . '/account/wallet'
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