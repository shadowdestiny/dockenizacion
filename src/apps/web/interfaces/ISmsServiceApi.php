<?php


namespace EuroMillions\web\interfaces;


interface ISmsServiceApi
{
    public function send($message, array $phone_numbers, $is_unicode);
}