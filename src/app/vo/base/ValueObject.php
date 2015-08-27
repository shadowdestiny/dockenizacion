<?php
namespace EuroMillions\vo\base;

use EuroMillions\interfaces\IValueObject;

abstract class ValueObject implements IValueObject
{
    protected function sameClassAs(IValueObject $object)
    {
        return \get_class($this) === \get_class($object);
    }

    /**
     * Compare two IValueObject and tells whether they can be considered equal
     *
     * @param  IValueObject $object
     * @return bool
     */
    public function sameValueAs(IValueObject $object)
    {
        return $this->sameClassAs($object) && $this->toNative() === $object->toNative();
    }
}