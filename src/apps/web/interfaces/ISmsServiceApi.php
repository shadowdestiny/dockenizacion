<?php


namespace EuroMillions\web\interfaces;


interface ISmsServiceApi
{
    public function send($apiKey);
}