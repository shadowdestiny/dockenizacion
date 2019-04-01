<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 28/10/18
 * Time: 09:43 PM
 */

namespace EuroMillions\megasena\emailTemplates;


use EuroMillions\web\emailTemplates\PowerBallPurchaseSubscriptionConfirmationEmailTemplate;

class MegaSenaPurchaseSubscriptionConfirmationEmailTemplate extends PowerBallPurchaseSubscriptionConfirmationEmailTemplate
{
    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            $template_id = "10395066";
            $subject = 'Поздравляем';
        } else {
            $template_id = "10394667";
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
}