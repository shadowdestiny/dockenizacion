<?php


namespace EuroMillions\web\emailTemplates;


class WinEmailTemplate extends EmailTemplateDecorator
{

    protected $user;

    protected $result_amount;

    public function loadVars()
    {
        $vars = [
            'template' => 'win-email',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name'    => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name'    => 'winning',
                        'content' => $this->result_amount->getAmount() / 10000
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => $this->config->domain['url'] . 'play'
                    ],
                    [
                        'name'    => 'url_account',
                        'content' => $this->config->domain['url'] . 'account/wallet'
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

    /**
     * @return mixed
     */
    public function getResultAmount()
    {
        return $this->result_amount;
    }

    /**
     * @param mixed $result_amount
     */
    public function setResultAmount($result_amount)
    {
        $this->result_amount = $result_amount;
    }

    public function loadHeader()
    {
        return $this->emailTemplate->loadHeader();
    }

    public function loadFooter()
    {
        return $this->emailTemplate->loadFooter();
    }

}