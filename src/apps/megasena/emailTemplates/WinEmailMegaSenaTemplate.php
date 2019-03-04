<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 13/12/18
 * Time: 05:53 PM
 */

namespace EuroMillions\megasena\emailTemplates;


use EuroMillions\web\emailTemplates\WinEmailPowerBallTemplate;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;

class WinEmailMegaSenaTemplate extends WinEmailPowerBallTemplate
{
    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {
        $strategy = $strategy ? $strategy : new JackpotDataEmailTemplateStrategy();
        $data = $strategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            // Welcome Email Russian Version Template ID= 3997341
            $template_id = "10396090";
            $subject = 'Поздравляем, Вы выиграли';
        } else {
            // Welcome Email English Version Template ID= 4021147
            $template_id = "10395072"; //testing
            $subject = 'Congratulations you have won!';
        }
        $vars = [
            'template' => $template_id,
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'winning_line',
                        'content' => $this->getWinningLine(),
                    ],
                    [
                        'name' => 'num_balls',
                        'content' => $this->getNummBalls()
                    ],
                    [
                        'name' => 'num_stars',
                        'content' => $this->getStarBalls()
                    ],
                    [
                        'name' => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name' => 'winning',
                        'content' => number_format((float)$this->result_amount->getAmount() / 100, 2, ".", ",")
                    ],
                    [
                        'name' => 'url_play',
                        'content' => $this->config . '/play'
                    ],
                    [
                        'name' => 'url_account',
                        'content' => $this->config . '/account/wallet'
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => $data['draw_day_format_one']
                    ],
                    [
                        'name' => 'draw_day_format_two',
                        'content' => $data['draw_day_format_two']
                    ],
                    [
                        'name' => 'jackpot_amount',
                        'content' => $data['jackpot_amount']
                    ],
                ]
        ];

        $data = $this->emailTemplateDataStrategy->getData($strategy);
        if ($this->user->getUserCurrency()->getName() != 'EUR') {
            $vars['vars'][] = [
                'name' => 'amount_converted',
                'content' => $data['amount_converted']
            ];
        }
        return $vars;
    }
}