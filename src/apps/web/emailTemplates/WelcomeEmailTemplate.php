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