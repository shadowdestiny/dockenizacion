<?php
namespace EuroMillions\vo\base;

use EuroMillions\exceptions\InvalidNativeArgumentException;
use EuroMillions\interfaces\IValueObject;

abstract class NullValue implements IValueObject
{
    protected $value = null;

    public function __construct($value=null)
    {
        if (!empty($value)) {
            throw new InvalidNativeArgumentException($value, ['null','\'\'']);
        }
        $this->value = $value;
    }

    public static function fromNative()
    {
        return new static(\func_get_arg(0));
    }

    /**
     * Returns a native PHP value
     *
     * @return null
     */
    public function toNative()
    {
        return $this->value;
    }

    /**
     * Compare two IValueObject and tells whether they can be considered equal
     *
     * @param  IValueObject $object
     * @return bool
     */
    public function sameValueAs(IValueObject $object)
    {
        return \get_class($object) === \get_class($this);
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return \strval($this->value);
    }
}