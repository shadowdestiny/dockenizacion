<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IToArrayStrategy;

class EntityToArrayStrategy implements IToArrayStrategy
{
    /**
     * @param $property
     * @param IEntity $value
     * @return array
     */
    public function getArray($property, $value)
    {
        return [StringHelper::fromCamelCaseToUnderscore($property).'_id' => $value->getId()];
    }
}