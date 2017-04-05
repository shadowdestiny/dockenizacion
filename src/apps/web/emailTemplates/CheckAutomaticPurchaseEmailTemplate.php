<?php


namespace EuroMillions\web\emailTemplates;


class CheckAutomaticPurchaseEmailTemplate extends EmailTemplateDecorator
{

    protected $users;
    protected $subscriptionsActives;
    protected $subscriptionsPlayed;

    public function loadVars()
    {
        $vars = [
            'template' => '1475123',
            'subject' => 'Automatic Purchase Not Played',
            'vars' =>
                [
                    [
                        'name' => 'users_list',
                        'content' => $this->getUsers()
                    ],
                    [
                        'name' => 'subscriptions_actives',
                        'content' => $this->getSubscriptionsActives()
                    ],
                    [
                        'name' => 'subscriptions_played',
                        'content' => $this->getSubscriptionsPlayed()
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
            $usersList[]['user'] = $user['user_id'];
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
    public function getSubscriptionsActives()
    {
        return $this->subscriptionsActives;
    }

    /**
     * @param $subscriptionsActives
     */
    public function setSubscriptionsActives($subscriptionsActives)
    {
        $this->subscriptionsActives = $subscriptionsActives;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionsPlayed()
    {
        return $this->subscriptionsPlayed;
    }

    /**
     * @param $subscriptionsPlayed
     */
    public function setSubscriptionsPlayed($subscriptionsPlayed)
    {
        $this->subscriptionsPlayed = $subscriptionsPlayed;
    }
}