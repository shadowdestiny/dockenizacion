<?php
namespace EuroMillions\web\vo\base;

use Assert\Assertion;
use Assert\AssertionFailedException;
use EuroMillions\web\exceptions\InvalidNativeArgumentException;

class Integer extends Real
{
    public function __construct($value)
    {
        try {
            Assertion::integer($value);
        } catch (AssertionFailedException $e) {
            throw new InvalidNativeArgumentException($value, ['integer']);
        }
        parent::__construct($value);
    }
}