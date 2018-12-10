<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 28/10/18
 * Time: 03:33 PM
 */

namespace EuroMillions\megamillions\emailTemplates;


use EuroMillions\web\emailTemplates\PowerBallPurchaseConfirmationEmailTemplate;

class MegaMillionsPurchaseConfirmationEmailTemplate extends PowerBallPurchaseConfirmationEmailTemplate
{
    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            $template_id = "8766805";
            $subject = 'Поздравляем';
        } else {
            $template_id = "8741711";
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