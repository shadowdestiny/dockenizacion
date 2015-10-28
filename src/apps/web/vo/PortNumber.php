<?php
namespace EuroMillions\web\vo;

use Assert\Assertion;
use EuroMillions\web\interfaces\IPortNumber;
use EuroMillions\web\vo\base\Natural;

class PortNumber extends Natural implements IPortNumber
{
    public function __construct($number)
    {
        Assertion::max($number, 65535);
        parent::__construct($number);
    }
}