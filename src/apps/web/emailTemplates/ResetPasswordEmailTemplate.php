<?php
namespace EuroMillions\web\emailTemplates;

class ResetPasswordEmailTemplate extends EmailTemplateDecorator
{


    public function loadVars()
    {
        $vars = [
            'template' => 'simple',
            'subject' => 'Your password in Euromillions.com has been changed',
            'vars' =>
                [
                    [
                        'name'    => 'main',
                        'content' => 'We have received a request to update your password. If you didn\'t make the request, please contact us at
                                     <a href="mailto:support@euromilions.com">support@euromilions.com</a>'
                    ],
                ]
        ];
        return $vars;

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