<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\web\interfaces\IToArrayStrategy;

class DateTimeObjectToArrayStrategy implements IToArrayStrategy
{
    /**
     * @param $property
     * @param \DateTime $value
     * @return array<string,string>
     */
    public function getArray($property, $value)
    {
        return [StringHelper::fromCamelCaseToUnderscore($property) => $value->format('Y-m-d H:i:s.u')];
    }
}