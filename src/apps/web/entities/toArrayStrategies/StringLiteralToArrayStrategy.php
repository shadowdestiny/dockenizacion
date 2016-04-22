<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\web\interfaces\IToArrayStrategy;

class StringLiteralToArrayStrategy implements IToArrayStrategy
{
    public function getArray($property, $value)
    {
        return $value->toArray();
    }
}