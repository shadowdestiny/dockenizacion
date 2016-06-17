<?php
namespace EuroMillions\web\entities;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\web\entities\toArrayStrategies\CurrencyObjectToArrayStrategy;
use EuroMillions\web\entities\toArrayStrategies\DateTimeObjectToArrayStrategy;
use EuroMillions\web\entities\toArrayStrategies\EntityToArrayStrategy;
use EuroMillions\web\entities\toArrayStrategies\MoneyObjectToArrayStrategy;
use EuroMillions\web\entities\toArrayStrategies\NonObjectToArrayStrategy;
use EuroMillions\web\entities\toArrayStrategies\StringLiteralToArrayStrategy;
use EuroMillions\web\entities\toArrayStrategies\ValueObjectToArrayStrategy;
use EuroMillions\web\exceptions\BadEntityInitializationException;
use EuroMillions\web\interfaces\IToArrayStrategy;
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
        $object_as_array = (array)$this->toValueObject();

        foreach ($object_as_array as $property => $value) {
            $strategy = $this->getStrategy($value);
            unset($object_as_array[$property]);
            $object_as_array = array_merge($object_as_array, $strategy->getArray($property, $value));
        }
        return $object_as_array;
    }

    /**
     * @param $value
     * @return IToArrayStrategy
     */
    private function getStrategy($value)
    {
        if (!is_object($value)) {
            return new NonObjectToArrayStrategy();
        } elseif (is_a($value, get_class())) {
            return new EntityToArrayStrategy();
        } elseif (strpos(get_class($value), 'vo') !== false) {
            if (is_a($value, 'EuroMillions\web\vo\base\StringLiteral')) {
                return new StringLiteralToArrayStrategy();
            } else {
                return new ValueObjectToArrayStrategy();
            }
        } elseif (is_a($value, 'Money\Currency')) {
            return new CurrencyObjectToArrayStrategy();
        } elseif (is_a($value, 'Money\Money')) {
            return new MoneyObjectToArrayStrategy();
        } elseif (is_a($value, 'DateTime')) {
            return new DateTimeObjectToArrayStrategy();
        }
    }
}