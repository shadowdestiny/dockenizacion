<?php


namespace EuroMillions\web\emailTemplates;

use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;


class LatestResultsEmailTemplate extends EmailTemplateDecorator
{

    protected $break_down_list;

    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {
        $strategy = $strategy ? $strategy : new JackpotDataEmailTemplateStrategy();
        $data = $this->emailTemplateDataStrategy->getData($strategy);
        $draw_result = $data['draw_result'];
        $jackpot = $data['jackpot_amount'];
        $last_draw_date = $data['last_draw_date'];

        $amount = number_format((float) $jackpot->getAmount() / 100,2,".",",");

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
                        'content' => $amount
                    ],
                    [
                        'name'    => 'draw_date',
                        'content' => $last_draw_date->format('j F Y')
                    ],
                    [
                        'name'    => 'regular_numbers',
                        'content' => $draw_result['regular_numbers']
                    ],
                    [
                        'name'    => 'lucky_numbers',
                        'content' => $draw_result['lucky_numbers']
                    ],
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