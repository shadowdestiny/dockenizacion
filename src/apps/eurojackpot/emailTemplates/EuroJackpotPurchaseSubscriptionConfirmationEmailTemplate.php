<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 28/10/18
 * Time: 09:43 PM
 */

namespace EuroMillions\eurojackpot\emailTemplates;


use EuroMillions\web\emailTemplates\PowerBallPurchaseSubscriptionConfirmationEmailTemplate;

class EuroJackpotPurchaseSubscriptionConfirmationEmailTemplate extends PowerBallPurchaseSubscriptionConfirmationEmailTemplate
{
    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            $template_id = "9747067";
            $subject = 'Поздравляем';
        } else {
            $template_id = "9747340";
            $subject = 'Congratulations';
        }

        $vars = [
            'template' => $template_id,
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'line',
                        'content' => $this->getLine(),
                    ],
                    [
                        'name' => 'user_name',
                        'content' => $this->user->getName()
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
                        'name' => 'frequency',
                        'content' => $this->getFrequency()
                    ],
                    [
                        'name' => 'starting_date',
                        'content' => $this->getStartingDate()
                    ],
                    [
                        'name' => 'draws',
                        'content' => $this->getDraws()
                    ],
                    [
                        'name' => 'jackpot',
                        'content' => $this->getJackpot()
                    ]
                ]
        ];

        return $vars;
    }

    /**
     * @return mixed
     */
    public function getLine()
    {
        $lines = [];

        if(!is_array($this->line)) {
            $this->line = [$this->line];
        }
        /** @var PlayConfig $line */
        foreach ($this->line as $line) {
            $play = [];
            foreach ($line->getLine()->getRegularNumbersArray() as $balls) {
                $play['regular_numbers'][]['number'] = $balls;
            }
            foreach ($line->getLine()->getLuckyNumbersArray() as $lucky) {
                $play['lucky_numbers'][]['number'] = $lucky;
            }
            array_push($lines, $play);
        }
        return $lines;
    }
}