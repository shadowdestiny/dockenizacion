<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\User;

class WinEmailTemplate extends EmailTemplateDecorator
{

    /** @var  User */
    protected $user;

    protected $result_amount;

    protected $winningLine;

    protected $nummBalls;

    protected $starBalls;

    public function loadVars()
    {

        $data = $this->emailTemplateDataStrategy->getData();

        $vars = [
            'template' => '625142',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name' => 'winning_line',
                        'content' => $this->getWinningLine(),
                    ],
                    [
                        'name' => 'num_balls',
                        'content' => $this->getNummBalls()
                    ],
                    [
                        'name' => 'star_balls',
                        'content' => $this->getStarBalls()
                    ],
                    [
                        'name'    => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name'    => 'winning',
                        'content' => number_format((float) $this->result_amount->getAmount() / 100,2,".",",")
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => $this->config . '/play'
                    ],
                    [
                        'name'    => 'url_account',
                        'content' => $this->config . '/account/wallet'
                    ]
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

    /**
     * @return mixed
     */
    public function getWinningLine()
    {
        return $this->winningLine;
    }

    /**
     * @param mixed $winningLine
     */
    public function setWinningLine($winningLine)
    {
        $this->winningLine = $winningLine;
    }

    /**
     * @return mixed
     */
    public function getNummBalls()
    {
        return $this->nummBalls;
    }

    /**
     * @param mixed $nummBalls
     */
    public function setNummBalls($nummBalls)
    {
        $this->nummBalls = $nummBalls;
    }

    /**
     * @return mixed
     */
    public function getStarBalls()
    {
        return $this->starBalls;
    }

    /**
     * @param mixed $starBalls
     */
    public function setStarBalls($starBalls)
    {
        $this->starBalls = $starBalls;
    }

}