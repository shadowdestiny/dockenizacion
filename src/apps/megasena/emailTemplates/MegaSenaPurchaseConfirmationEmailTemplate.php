<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 28/10/18
 * Time: 03:33 PM
 */

namespace EuroMillions\megasena\emailTemplates;


use EuroMillions\web\emailTemplates\PowerBallPurchaseConfirmationEmailTemplate;

class MegaSenaPurchaseConfirmationEmailTemplate extends PowerBallPurchaseConfirmationEmailTemplate
{
    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            $template_id = "10394012";
            $subject = 'Поздравляем';
        } else {
            $template_id = "10394410";
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
                        'name'    => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => $data['draw_day_format_one']
                    ],
                    [
                        'name' => 'draw_day_format_two',
                        'content' => $data['draw_day_format_two']
                    ]
                ]
        ];

        return $vars;
    }
}