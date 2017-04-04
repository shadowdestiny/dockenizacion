<?php

namespace EuroMillions\web\emailTemplates;

class JackpotRolloverEmailTemplate extends EmailTemplateDecorator
{

    protected $user;
    protected $threshold_amount;

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();

        $time_config = $data['time_close'];
        $draw_day_format_one = $data['draw_day_format_one'];
        $draw_day_format_two = $data['draw_day_format_two'];
        $jackpot_amount = $data['jackpot_amount'];

        $vars = [
            'template' => '625301',
            'subject' => 'The Jackpot has reached your threshold',
            'vars' =>
                [
                    [
                        'name' => 'user_name',
                        'content' => $this->user->getName()
                    ],
                    [
                        'name' => 'player_alert_threshold',
                        'content' => '€' . number_format((float) $this->getThresholdAmount(),0,".",",")
                    ],
                    [
                        'name'    => 'current_jackpot',
                        'content' => $jackpot_amount
                    ],
                    [
                        'name'    => 'draw_day_format_one',
                        'content' => $draw_day_format_one
                    ],
                    [
                        'name'    => 'draw_day_format_two',
                        'content' => $draw_day_format_two,
                    ],
                    [
                        'name'    => 'time_closed',
                        'content' => $time_config . ' CET'
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => $this->config . '/play'
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
    public function getThresholdAmount()
    {
        return $this->threshold_amount;
    }

    /**
     * @param mixed $threshold_amount
     */
    public function setThresholdAmount($threshold_amount)
    {
        $this->threshold_amount = $threshold_amount;
    }
}