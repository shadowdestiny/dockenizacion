<?php


namespace EuroMillions\web\emailTemplates;


class WelcomeEmailTemplate extends EmailTemplateDecorator
{

    protected $user;

    public function loadVars()
    {
        $vars = [
            'template' => 'welcome',
            'subject' => 'Welcome to Euromillions.com',
            'vars' => [
                [
                    'name' => 'user_name',
                    'content' => $this->user->getName()
                ],
                [
                    'name' => 'howToPlay',
                    'content' => $this->config->domain['url'] . '/help'
                ],
                [
                    'name' => 'numbers',
                    'content' => $this->config->domain['url'] . '/numbers'
                ],
                [
                    'name' => 'subscribe',
                    'content' => $this->config->domain['url'] . '/numbers'
                ],
                [
                    'name' => 'faq',
                    'content' => $this->config->domain['url'] . '/faq'
                ],
                [
                    'name' => 'contact',
                    'content' => 'mailto:support@euromillions.com'
                ]
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
        // TODO: Implement loadHeader() method.
    }

    public function loadFooter()
    {
        // TODO: Implement loadFooter() method.
    }
}