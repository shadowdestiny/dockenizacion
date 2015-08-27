<?php
namespace EuroMillions\vo;

use Assert\Assertion;
use EuroMillions\interfaces\IPortNumber;
use EuroMillions\vo\base\Natural;

class PortNumber extends Natural implements IPortNumber
{
    public function __construct($number)
    {
        Assertion::max($number, 65535);
        parent::__construct($number);
    }
}