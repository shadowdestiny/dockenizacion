<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;

class PurchaseConfirmationEmailTemplate extends EmailTemplateDecorator
{

    /** @var  User */
    protected $user;

    protected $line;

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $vars = [
            'template' => '624539',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name' => 'line',
                        'content' => $this->getLine(),
                    ],
                    [
                        'name'    => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => $data['draw_day_format_one']
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => $data['draw_day_format_one']
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
        foreach($this->line as $line) {
            $play = [];
            foreach($line->getLine()->getRegularNumbersArray() as $balls) {
                $play['regular_numbers'][]['number'] = $balls;
             }
            foreach($line->getLine()->getLuckyNumbersArray() as $stars) {
                $play['lucky_numbers'][]['number'] = $stars;
            }
            array_push($lines,$play);
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