<?php


namespace EuroMillions\web\emailTemplates;


class ForgotPasswordEmailTemplate extends EmailTemplateDecorator
{

    protected $url;

    public function loadVars()
    {
        $vars = [
            'template' => '625282',
            'subject' => 'Your password in Euromillions.com has been changed',
            'vars' =>
                [
                    [
                        'name'    => 'main',
                        'content' => 'We have received a request to reset your password. If you didn\'t make the request, just ignore this email.<br>You can reset your password using this link: <a href="'.$this->getUrl()->toNative().'">Click here to reset your password</a>
                                      <br><br>or copy and paste this url in your browser: '.$this->getUrl()->toNative() .''
                    ],
                ]
        ];
        return $vars;
    }

    public function loadHeader()
    {
        // TODO: Implement loadHeader() method.
    }

    public function loadFooter()
    {
        // TODO: Implement loadFooter() method.
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}