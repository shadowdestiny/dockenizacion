<?php


namespace EuroMillions\web\interfaces;


interface ICaptcha
{
    public function html();
    public function check();
}