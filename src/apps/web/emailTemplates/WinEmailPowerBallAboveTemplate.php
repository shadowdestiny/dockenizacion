<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\User;


class WinEmailPowerBallAboveTemplate extends EmailTemplateDecorator
{

    /** @var  User $user */
    protected $user;

    protected $result_amount;

    protected $winningLine;

    protected $nummBalls;

    protected $starBalls;

    public function loadVars()
    {

        $data = $this->emailTemplateDataStrategy->getData();
        $language = $this->user->getDefaultLanguage();

        if ($language == "ru") {
            // Win Email Russian Version Template ID= 4020263
            $template_id = "7325646";
            $subject = 'Поздравляем';
        } else {
            $template_id = "7325585";
            $subject = 'Congratulations';
        }

        $vars = [
            'template' => $template_id, // Old template email ID
            'subject' => $subject,
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
                        'name' => 'num_stars',
                        'content' => $this->getStarBalls()
                    ],
                    [
                        'name' => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name' => 'winning',
                        'content' => number_format((float)$this->result_amount->getAmount() / 100, 2, ".", ",")
                    ],
                ]
        ];

        if ($this->user->getUserCurrency()->getName() != 'EUR') {
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