<?php


namespace EuroMillions\web\emailTemplates;

class WelcomeEmailTemplate extends EmailTemplateDecorator
{

    protected $user;

    public function loadVars()
    {
        $data = $this->emailTemplateDataStrategy->getData();
        $vars = [
            'template' => '623001',
            'subject' => 'Welcome to Euromillions.com',
            'vars' => [
                [
                    'name' => 'user_name',
                    'content' => $this->user->getName()
                ],
                [
                    'name' => 'howToPlay',
                    'content' => $this->config . '/help'
                ],
                [
                    'name' => 'numbers',
                    'content' => $this->config . '/numbers'
                ],
                [
                    'name' => 'subscribe',
                    'content' => $this->config . '/numbers'
                ],
                [
                    'name' => 'faq',
                    'content' => $this->config . '/faq'
                ],
                [
                    'name' => 'contact',
                    'content' => 'mailto:support@euromillions.com'
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
        // TODO: Implement loadFooter() method.
    }
}