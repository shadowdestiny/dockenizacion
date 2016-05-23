<?php


namespace EuroMillions\web\emailTemplates;

class JackpotRolloverEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();

        $time_config = $data['time_close'];
        $draw_day_format_one = $data['draw_day_format_one'];
        $draw_day_format_two = $data['draw_day_format_two'];
        $jackpot_amount = $data['jackpot_amount'];

        $vars = [
            'template' => '625301',
            'subject' => 'The Jackpot has reached your threshold',
            'vars' =>
                [
                    [
                        'name'    => 'current_jackpot',
                        'content' => number_format((float) $jackpot_amount->getAmount() / 100,2,".",",")
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
                        'name'    => 'time_closed',
                        'content' => $time_config . ' CET'
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