<?php
namespace EuroMillions\vo\base;

use EuroMillions\exceptions\InvalidNativeArgumentException;
use EuroMillions\interfaces\IValueObject;

class StringLiteral extends ValueObject
{
    protected $value;

    public function __construct($value)
    {
        if (false === \is_string($value)) {
            throw new InvalidNativeArgumentException($value, ['string']);
        }
        $this->value = $value;
    }

    /**
     * Returns a object taking PHP native value(s) as argument(s).
     *
     * @return IValueObject
     */
    public static function fromNative()
    {
        return new static(func_get_arg(0));
    }

    public function toNative()
    {
        return $this->value;
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toNative();
    }

    public function isEmpty()
    {
        return \strlen($this->toNative()) == 0;
    }
}