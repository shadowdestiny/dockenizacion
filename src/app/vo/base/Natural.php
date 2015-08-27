<?php
namespace EuroMillions\vo\base;

use Assert\Assertion;
use Assert\AssertionFailedException;
use EuroMillions\exceptions\InvalidNativeArgumentException;

class Natural extends Integer
{
    public function __construct($value)
    {
        try {
            Assertion::min($value, 0);
        } catch (AssertionFailedException $e) {
            throw new InvalidNativeArgumentException($value, ['integer (>= 0)']);
        }
        parent::__construct($value);
    }
}