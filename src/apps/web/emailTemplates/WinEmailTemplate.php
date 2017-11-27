<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;

class WinEmailTemplate extends EmailTemplateDecorator
{

    /** @var  User */
    protected $user;

    protected $result_amount;

    protected $winningLine;

    protected $nummBalls;

    protected $starBalls;

    public function loadVars(IEmailTemplateDataStrategy $strategy = null)
    {
        $strategy = $strategy ? $strategy : new JackpotDataEmailTemplateStrategy();
        $data = $strategy->getData();

//        $language=$this->user->getDefaultLanguage();
//
//        if ($language="en") {
//            // Win Email English Version Template ID= 625142
//            $template_id="625142";
//        } elseif ($language="ru") {
//            // Win Email Russian Version Template ID= 4020263
//            $template_id="4020263";
//        } else {
//            $template_id="625142";
//        }

        $vars = [
//            'template' => $template_id,
            'template' => '4020263',
//            'template' => '625142', Old design

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
                        'name' => 'num_stars',
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

        $data = $this->emailTemplateDataStrategy->getData($strategy);
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