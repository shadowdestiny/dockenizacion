<?php
namespace EuroMillions\web\entities;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\shared\interfaces\IArraySerializable;
use EuroMillions\web\exceptions\BadEntityInitializationException;
use Money\Currency;
use Money\Money;

class EntityBase
{
    /**
     * @param array $attributes
     * @return $this
     * @throws BadEntityInitializationException
     */
    public function initialize($attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($key === 'id') {
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
            $propertyUnderscore = StringHelper::fromUnderscoreToCamelCase($property);
            $getter = 'get' . ucfirst($propertyUnderscore);
            if (method_exists($this, $getter) && (!is_a($this->$getter(), 'Doctrine\Common\Collections\ArrayCollection'))) {
                $result->$property = $this->$getter();
            }
        }
        return $result;
    }

    public function toArray()
    {
        $object_as_array = (array) $this->toValueObject();
        foreach($object_as_array as $property => $value) {
            if (is_object($value)) {
                $namespace = get_class($value);
                if(strpos($namespace,'vo') !== false ) {
                    /** @var IArraySerializable $value */
                    unset($object_as_array[$property]);
                    $object_as_array = array_merge($object_as_array, $value->toArray());
                } else {
                    $property_cache = $property;
                    unset($object_as_array[$property]);
                    $object_as_array = array_merge($object_as_array, $this->getStringValueFromObject($value,$property_cache));
                }
            }
            $property_with_underscores = StringHelper::fromCamelCaseToUnderscore($property);
            if ($property !== $property_with_underscores) {
                unset($object_as_array[$property]);
                $object_as_array[$property_with_underscores] = $value;
            }
        }

        return $object_as_array;
    }

    //EMTD remove this method and create a strategy pattern
    protected function getStringValueFromObject($value, $property)
    {
        if($value instanceof Currency) {
            return [
                'user_currency_name' => $value->getName()
            ];
        }
        if($value instanceof Money) {
            return [
                $property.'_amount' => $value->getAmount(),
                $property.'_currency_name' => $value->getCurrency()->getName()
            ];
        }
    }
}