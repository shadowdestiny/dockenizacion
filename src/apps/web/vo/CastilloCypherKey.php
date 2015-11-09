<?php

namespace EuroMillions\web\vo;


use EuroMillions\web\exceptions\InvalidNativeArgumentException;

class CastilloCypherKey
{

    private $key;

    public function __construct($number)
    {
        if(!$this->checkNumberBetween($number)){
            throw new InvalidNativeArgumentException('Number should be between 0 and 9',['int']);
        }
        $this->key = $number;
    }


    public static function create()
    {
        return new static(rand(0,9));
    }


    private function checkNumberBetween($number)
    {
        if(!is_numeric($number)) {
            return false;
        }
        if($number < 0 || $number > 9) {
            return false;
        }

        return true;
    }

    public function key()
    {
        return $this->key;
    }

}