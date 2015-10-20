<?php


namespace EuroMillions\interfaces;


interface ILotteryValidation
{

    public function request($bet);
    public function response();

}