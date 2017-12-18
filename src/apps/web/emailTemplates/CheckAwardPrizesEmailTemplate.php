<?php


namespace EuroMillions\web\emailTemplates;


class CheckAwardPrizesEmailTemplate extends EmailTemplateDecorator
{

    protected $users;
    protected $usersAwarded;
    protected $usersPlayed;

    public function loadVars()
    {
        $vars = [
            'template' => '4261765',
            'subject' => 'Prizes Awarded',
            'vars' =>
                [
                    [
                        'name' => 'users_list',
                        'content' => $this->getUsers()
                    ],
                    [
                        'name' => 'subscriptions_actives',
                        'content' => $this->getUsersAwarded()
                    ],
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
    public function getUsers()
    {
        $usersList = [];
        foreach($this->users as $user) {
            $usersList[]['user'] = $user;
        }

        return $usersList;
    }

    /**
     * @param array $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getUsersAwarded()
    {
        return $this->usersAwarded;
    }

    /**
     * @param mixed $usersAwarded
     */
    public function setUsersAwarded($usersAwarded)
    {
        $this->usersAwarded = $usersAwarded;
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