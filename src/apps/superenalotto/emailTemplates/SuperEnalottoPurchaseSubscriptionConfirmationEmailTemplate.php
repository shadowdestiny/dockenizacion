<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 28/10/18
 * Time: 09:43 PM
 */

namespace EuroMillions\superenalotto\emailTemplates;


use EuroMillions\web\emailTemplates\PowerBallPurchaseSubscriptionConfirmationEmailTemplate;

class SuperEnalottoPurchaseSubscriptionConfirmationEmailTemplate extends PowerBallPurchaseSubscriptionConfirmationEmailTemplate
{
    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            $template_id = "11467325";
            $subject = 'Поздравляем';
        } else {
            $template_id = "11467448";
            $subject = 'Congratulations';
        }

        $arr= $this->getLine();

        foreach($arr as &$item)
        {
            usort($item['regular_numbers'], function ($a, $b)
            {
                return $a['number'] - $b['number'];
            });

            if($item['regular_numbers'][4]['number']>$item['lucky_numbers'][0]['number'])
            {
                $aux=$item['lucky_numbers'][0]['number'];
                $item['lucky_numbers'][0]['number']=$item['regular_numbers'][4]['number'];
                $item['regular_numbers'][4]['number']= $aux;
            }
            usort($item['regular_numbers'], function ($a, $b)
            {
                return $a['number'] - $b['number'];
            });
        }

        $vars = [
            'template' => $template_id,
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'line',
                        'content' => $arr,
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
}