<?php


namespace EuroMillions;


use EuroMillions\interfaces\ICypherStrategy;

class NullCypher implements ICypherStrategy
{

    public function encrypt($clear)
    {

    }

    public function decrypt($cyphered, $key)
    {

    }

    public function getCypherResult()
    {

    }
}