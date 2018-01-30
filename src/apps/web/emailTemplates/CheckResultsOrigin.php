<?php

namespace EuroMillions\web\emailTemplates;


class CheckResultsOrigin extends EmailTemplateDecorator
{
    protected $usersPlayed;

    public function loadVars()
    {
        $vars = [
            'template' => '4741721',
            'subject' => 'Results Origin',
            'vars' =>
                [
                    [
                        'name' => 'subscriptions_played',
                        'content' => $this->getUsersPlayed()
                    ]
                ]
        ];

        return $vars;
    }

    public function loadHeader()
    {

    }

    public function loadFooter()
    {

    }

    /**
     * @return mixed
     */
    public function getUsersPlayed()
    {
        return $this->usersPlayed;
    }

    /**
     * @param mixed $usersPlayed
     */
    public function setUsersPlayed($usersPlayed)
    {
        $this->usersPlayed = $usersPlayed;
    }
}