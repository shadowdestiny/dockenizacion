<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\vo\EuroMillionsLine;

class LatestResultsEmailTemplate extends EmailTemplateDecorator
{

    protected $break_down_list;

    public function loadVars()
    {
        /** @var EuroMillionsLine $draw_result */
        $draw_result = $this->lotteriesDataService->getLastResult('EuroMillions');
        $jackpot = $this->lotteriesDataService->getLastJackpot('EuroMillions');
        $last_draw_date = $this->lotteriesDataService->getLastDrawDate('EuroMillions')->format('j F Y');

        $vars = [
            'template' => 'latest-results',
            'subject' => 'Latest results',
            'vars' =>
                [
                    [
                        'name'    => 'breakdown',
                        'content' => $this->break_down_list
                    ],
                    [
                        'name'    => 'jackpot',
                        'content' => $jackpot->getAmount()/100
                    ],
                    [
                        'name'    => 'draw_date',
                        'content' => $last_draw_date
                    ],
                    [
                        'name'    => 'regular_numbers',
                        'content' => $draw_result['regular_numbers']
                    ],
                    [
                        'name'    => 'lucky_numbers',
                        'content' => $draw_result['lucky_numbers']
                    ]
                ]
        ];

        return $vars;
    }

    /**
     * @return mixed
     */
    public function getBreakDownList()
    {
        return $this->break_down_list;
    }

    /**
     * @param mixed $break_down_list
     */
    public function setBreakDownList($break_down_list)
    {
        $this->break_down_list = $break_down_list;
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