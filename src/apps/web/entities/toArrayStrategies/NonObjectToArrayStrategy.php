<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\web\interfaces\IToArrayStrategy;

class NonObjectToArrayStrategy implements IToArrayStrategy
{
    /**
     * @param $property
     * @param $value
     * @return array
     */
    public function getArray($property, $value)
    {
        return [StringHelper::fromCamelCaseToUnderscore($property) => $value];
    }
}