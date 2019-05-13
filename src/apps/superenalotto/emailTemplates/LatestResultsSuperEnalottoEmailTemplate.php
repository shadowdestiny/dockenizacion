<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 29/10/18
 * Time: 03:40 PM
 */

namespace EuroMillions\superenalotto\emailTemplates;

use EuroMillions\web\emailTemplates\LatestResultsPowerBallEmailTemplate;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;

class LatestResultsSuperEnalottoEmailTemplate extends LatestResultsPowerBallEmailTemplate
{
    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {
        $strategy = $strategy ? $strategy : new LatestResultsDataEmailTemplateStrategy();
        $data = $this->emailTemplateDataStrategy->getData($strategy);
        $draw_result = $data['draw_result'];
        $jackpot = $data['jackpot_amount'];
        $last_draw_date = $data['last_draw_date'];
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            // Welcome Email Russian Version Template ID= 3997341
            $template_id = "11466565";
            $subject = 'Результаты розыгрышей последней лотереи';
        } else {
            // Welcome Email English Version Template ID= 4021147
            $template_id = "11466019";
            $subject = 'Latest results';
        }


        $vars = [
            //'template' => '624601', // Old template email ID
            'template' => $template_id,
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'breakdown',
                        'content' => $this->getBreakDownList()
                    ],
                    [
                        'name' => 'jackpot_amount',
                        'content' => $jackpot
                    ],
                    [
                        'name' => 'draw_date',
                        'content' => $last_draw_date->format('j F Y')
                    ],
                    [
                        'name' => 'regular_numbers',
                        'content' => $this->mapNumbers($draw_result['regular_numbers'])
                    ],
                    [
                        'name' => 'lucky_numbers',
                        'content' => $this->mapLuckyNumbers($draw_result['lucky_numbers'])
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => $data['draw_day_format_one']
                    ],
                    [
                        'name' => 'draw_day_format_two',
                        'content' => $data['draw_day_format_two']
                    ],
                ]
        ];

        return $vars;
    }
}