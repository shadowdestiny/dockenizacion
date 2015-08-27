<?php


namespace EuroMillions\vo\base;


use Assert\Assertion;
use Assert\AssertionFailedException;
use EuroMillions\exceptions\InvalidNativeArgumentException;
use EuroMillions\interfaces\IValueObject;

class Real extends ValueObject implements IValueObject
{
    protected $value;

    public function __construct($value)
    {
        try {
            Assertion::numeric(\filter_var($value, FILTER_VALIDATE_FLOAT));
        } catch (AssertionFailedException $e) {
            throw new InvalidNativeArgumentException($value, ['float']);
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
     * Returns a native PHP value
     *
     * @return mixed
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
        return \strval($this->toNative());
    }
}