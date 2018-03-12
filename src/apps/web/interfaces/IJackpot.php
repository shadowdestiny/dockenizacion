<?php


namespace EuroMillions\web\interfaces;


interface IJackpot
{
    public function getAmount();
    public function isValid();
    public function getCurrency();
    public function setAmount($amount);

    /**
     * @return string
     */
    public function toString();
}