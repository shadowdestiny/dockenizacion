<?php
namespace EuroMillions\vo;

use Assert\Assertion;
use EuroMillions\vo\base\Domain;

class Hostname extends Domain
{
    public function __construct($value)
    {
        Assertion::regex($value, '/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i'); //valid chars check
        Assertion::regex($value, '/^.{1,253}$/'); //overall length check
        Assertion::regex($value, '/^[^\.]{1,63}(\.[^\.]{1,63})*$/'); //length of each label
        parent::__construct($value);
    }

}