<?php


namespace EuroMillions\interfaces;


interface ICypherStrategy
{
    public function encrypt($clear);

    public function decrypt($cyphered, $key);

    public function getCypherResult();
}