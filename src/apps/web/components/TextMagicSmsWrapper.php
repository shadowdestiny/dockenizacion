<?php


namespace EuroMillions\web\components;


use EuroMillions\web\interfaces\ISmsServiceApi;
use TextMagicSMS\TextMagicAPI;


class TextMagicSmsWrapper implements ISmsServiceApi
{

    protected $textMagicAPI;

    public function __construct(array $apiKey, TextMagicAPI $textMagicAPI = null)
    {
        $this->textMagicAPI = $textMagicAPI ? $textMagicAPI : new TextMagicAPI($apiKey);
    }


    public function send($message, array $phone_numbers, $is_unicode)
    {
        $this->textMagicAPI->send($message, $phone_numbers, $is_unicode);
    }
}