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
        $template_id = $data['language']=='ru_RU'? '8169125':'8153316';
        $subject = $data['language']=='ru_RU' ? 'Ваша покупка не обработана правильно':'Your purchase has not been processed correctly';
        return $vars = [
            'template' => $template_id,
//            'template' => '624539', Old Template
            'subject' => $subject,
            'vars' =>
                [
                    [
                        'name' => 'lottery_name',
                        'content' => $data['lottery_name'],
                    ],
                    [
                        'name'    => 'user_name',
                        'content' => $data['user_name']
                    ],
                    [
                        'name' => 'deposit_name',
                        'content' => $data['deposit_name']
                    ],
                    [
                        'name' => 'date',
                        'content' => $data['date']
                    ],
                    [
                        'name' => 'date_header',
                        'content' => $data['date']
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