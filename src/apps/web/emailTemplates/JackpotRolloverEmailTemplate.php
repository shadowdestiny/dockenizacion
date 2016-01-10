<?php


namespace EuroMillions\web\emailTemplates;

class JackpotRolloverEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars()
    {
        $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $time_config = $this->domainServiceFactory->getServiceFactory()->getDI()->get('globalConfig')['retry_validation_time'];
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');
        $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');

        $vars = [
            'template' => 'jackpot-rollover',
            'subject' => 'Jackpot',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
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
                        'content' => $time_config['time'] . ' CET'
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