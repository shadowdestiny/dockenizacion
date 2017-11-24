<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;

class PurchaseConfirmationChristmasEmailTemplate extends EmailTemplateDecorator
{

    /** @var  User */
    protected $user;

    protected $line;

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();

        if ($language="en") {
            // Purchase Confirmation Christmas Email English Version Template ID= 2896261
            $template_id="2896261";
        } elseif ($language="ru") {
            // Purchase Confirmation Christmas Email Russian Version Template ID= 4020941
            $template_id="4020941";
        } else {
            $template_id="2896261";
        }

        $vars = [
            //'template' => '2896261',
            'template' => $template_id,
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
                        'name' => 'draw_day_format_two',
                        'content' => $data['draw_day_format_two']
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
            $play['regular_numbers'][]['number'] = $line->getNumber();
            $play['lucky_numbers'][]['number'] = $line->getSerieInit();
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