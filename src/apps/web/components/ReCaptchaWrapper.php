<?php


namespace EuroMillions\web\components;


use Captcha\Captcha;
use EuroMillions\web\interfaces\ICaptcha;


class ReCaptchaWrapper implements ICaptcha
{
    /** @var  Captcha */
    private $captcha;


    public function __construct($captcha)
    {
        $this->captcha = $captcha;
    }


    public function html()
    {
        return $this->captcha->html();
    }

    public function check()
    {
        return $this->captcha->check();
    }

    public function getCaptcha()
    {
        return $this->captcha;
    }

}