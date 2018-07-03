<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;

class PowerBallPurchaseSubscriptionConfirmationEmailTemplate extends EmailTemplateDecorator
{

    /** @var  User */
    protected $user;

    protected $line;

    protected $startingDate;

    protected $draws;
    protected $frequency;
    protected $jackpot;

    /**
     * @return mixed
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * @param mixed $startingDate
     */
    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;
    }

    /**
     * @return mixed
     */
    public function getDraws()
    {
        return $this->draws;
    }

    /**
     * @param mixed $draws
     */
    public function setDraws($draws)
    {
        $this->draws = $draws;
    }

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getJackpot()
    {
        return $this->jackpot;
    }

    /**
     * @param mixed $jackpot
     */
    public function setJackpot($jackpot)
    {
        $this->jackpot = $jackpot;
    }

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $vars = [
            'template' => '7289961',
//            'template' => '1169621', Old Template
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name' => 'line',
                        'content' => $this->getLine(),
                    ],
                    [
                        'name' => 'user_name',
                        'content' => $this->user->getName()
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
                        'name' => 'frequency',
                        'content' => $this->getFrequency()
                    ],
                    [
                        'name' => 'starting_date',
                        'content' => $this->getStartingDate()
                    ],
                    [
                        'name' => 'draws',
                        'content' => $this->getDraws()
                    ],
                    [
                        'name' => 'jackpot',
                        'content' => $this->getJackpot()
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
    public function getLine()
    {
        $lines = [];
        /** @var PlayConfig $line */
        foreach ($this->line as $line) {
            $play = [];
            foreach ($line->getLine()->getRegularNumbersArray() as $balls) {
                $play['regular_numbers'][]['number'] = $balls;
            }
            $lucky = $line->getLine()->getLuckyNumbersArray();
            $play['lucky_numbers'][]['number'] = $lucky[1];
//            foreach ($line->getLine()->getLuckyNumbersArray() as $stars) {
//                $play['lucky_numbers'][]['number'] = $stars;
//            }
            $play['power_play'] = $line->getPowerPlay();
            array_push($lines, $play);
        }
        return $lines;
    }

    /**
     * @param mixed $line
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}