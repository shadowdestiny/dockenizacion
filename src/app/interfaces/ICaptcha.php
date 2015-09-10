<?php


namespace EuroMillions\interfaces;


interface ICaptcha
{
    public function html();
    public function check();
}