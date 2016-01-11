<?php


namespace EuroMillions\web\emailTemplates;


class LowBalanceEmailTemplate extends EmailTemplateDecorator
{

    public function loadVars()
    {
        $jackpot = $this->lotteriesDataService->getNextJackpot('EuroMillions');
        $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');

        $vars = [
            'template' => 'low-balance',
            'subject' => 'Low balance',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => number_format((float) $jackpot->getAmount() / 100,2,".",",")
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
                        'content' => $this->config->domain['url'] . 'account/wallet'
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