<?php
namespace EuroMillions\web\interfaces;

interface IValueObject
{
    /**
     * Returns a object taking PHP native value(s) as argument(s).
     *
     * @return IValueObject
     */
    public static function fromNative();

    /**
     * Returns a native PHP value
     *
     * @return mixed
     */
    public function toNative();

    /**
     * Compare two IValueObject and tells whether they can be considered equal
     *
     * @param  IValueObject $object
     * @return bool
     */
    public function sameValueAs(IValueObject $object);

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function __toString();
}