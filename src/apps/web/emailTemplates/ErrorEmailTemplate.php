<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 31/08/18
 * Time: 13:50
 */

namespace EuroMillions\web\emailTemplates;


class ErrorEmailTemplate extends EmailTemplateDecorator
{


    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $template_id = '8153316';
        $subject = 'Your purchase has not been processed correctly';
        $vars = [
            'template' => $template_id,
//            'template' => '624539', Old Template
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'lottery_name',
                        'content' => $data['lottery'],
                    ],
                    [
                        'name'    => 'user_name',
                        'content' => $data['user_name']
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
    }

    public function loadHeader()
    {
        // TODO: Implement loadHeader() method.
    }

    public function loadFooter()
    {
        // TODO: Implement loadFooter() method.
    }
}