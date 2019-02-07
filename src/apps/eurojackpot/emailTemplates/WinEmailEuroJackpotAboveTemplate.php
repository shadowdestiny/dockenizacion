<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 29/10/18
 * Time: 04:41 PM
 */

namespace EuroMillions\eurojackpot\emailTemplates;


use EuroMillions\web\emailTemplates\WinEmailPowerBallAboveTemplate;

class WinEmailEuroJackpotAboveTemplate extends WinEmailPowerBallAboveTemplate
{
    public function loadVars()
    {

        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            // Win Email Russian Version Template ID= 4020263
            $template_id = "9747337";
            $subject = 'Поздравляем';
        } else {
            $template_id = "9747065";
            $subject = 'Congratulations';
        }

        $vars = [
            'template' => $template_id, // Old template email ID
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
                        'content' => number_format((float)$this->result_amount->getAmount(), 2, ".", ",")
                    ],
                ]
        ];

        if ($this->user->getUserCurrency()->getName() != 'EUR') {
            $vars['vars'][] = [
                'name' => 'amount_converted',
                'content' => $data['amount_converted']
            ];
        }

        return $vars;
    }

    /**
     * @return mixed
     */
    public function getStarBalls()
    {
        if ($this->starBalls == 0) {
            return null;
        }

        if ($this->starBalls == 1) {
            return '1 Euro Ball';
        }

        return '2 Euro Balls';
    }
}