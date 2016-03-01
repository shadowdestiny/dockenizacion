<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\User;


class WinEmailAboveTemplate extends EmailTemplateDecorator
{

    /** @var  User $user */
    protected $user;

    protected $result_amount;

    public function loadVars()
    {

        $data = $this->emailTemplateDataStrategy->getData();

        $vars = [
            'template' => 'win-email-above-1500',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name'    => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name'    => 'winning',
                        'content' => number_format((float) $this->result_amount->getAmount() / 100,2,".",",")
                    ],
                ]
        ];

        if( $this->user->getUserCurrency()->getName() != 'EUR' ) {
            $vars['vars'][] = [
                'name' => 'amount_converted',
                'content' => $data['amount_converted']
            ];
        }

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