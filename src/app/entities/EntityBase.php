<?php
namespace EuroMillions\entities;

use EuroMillions\exceptions\BadEntityInitializationException;

class EntityBase
{
    /**
     * @param array $attributes
     * @return $this
     */
    public function initialize($attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($key == 'id') {
                $this->$key = $value;
            } else {
                $field_name = implode('', array_map('ucfirst', explode('_', $key)));
                $setter = 'set' . $field_name;
                if (method_exists($this, $setter)) {
                    $this->$setter($value);
                } else {
                    throw new BadEntityInitializationException("Bad property name: \"$key\"");
                }
            }
        }
        return $this;
    }

    public function toValueObject()
    {
        $result = new \stdClass();
        foreach ($this as $property => $value) {
            $getter = 'get' . ucfirst($property);
            if (method_exists($this, $getter)) {
                $result->$property = $this->$getter();
            }
        }
        return $result;
    }
}