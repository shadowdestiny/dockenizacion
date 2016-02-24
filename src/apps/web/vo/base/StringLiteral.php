<?php
namespace EuroMillions\web\vo\base;

use EuroMillions\web\exceptions\InvalidNativeArgumentException;
use EuroMillions\web\interfaces\IValueObject;

class StringLiteral extends ValueObject
{
    /** @var string */
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

    /**
     * @return string
     */
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
        return $this->toNative() === '';
    }
}