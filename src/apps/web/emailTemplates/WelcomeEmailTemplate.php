<?php


namespace EuroMillions\web\emailTemplates;

use EuroMillions\web\entities\User;

class WelcomeEmailTemplate extends EmailTemplateDecorator
{
    /** @var  User $user */
    protected $user;


    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            // Welcome Email Russian Version Template ID= 3997341
            $template_id = "3997341";
            $subject = 'Добро пожаловать на EuroMillions.com';
        } else {
            // Welcome Email English Version Template ID= 4021147
            //$template_id = "4021147"; //prod
            $template_id = "4012194"; //testing
            $subject = 'Welcome to Euromillions.com';
        }

        $vars = [
            //'template' => '623001', // Old template email ID
            'template' => $template_id,
            'subject' => $subject,
            'vars' => [
                [
                    'name' => 'token',
                    'content' => $this->config . '/validate/'. $this->user->getValidationToken()->toNative()
                ],
                [
                    'name' => 'user_name',
                    'content' => $this->user->getName()
                ],
                [
                    'name' => 'howToPlay',
                    'content' => $this->config . '/help'
                ],
                [
                    'name' => 'numbers',
                    'content' => $this->config . '/numbers'
                ],
                [
                    'name' => 'subscribe',
                    'content' => $this->config . '/numbers'
                ],
                [
                    'name' => 'faq',
                    'content' => $this->config . '/faq'
                ],
                [
                    'name' => 'contact',
                    'content' => 'mailto:support@euromillions.com'
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

        return $vars;
    }



    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function loadHeader()
    {
        return $this->emailTemplate->loadHeader();
    }

    public function loadFooter()
    {
        // TODO: Implement loadFooter() method.
    }
}