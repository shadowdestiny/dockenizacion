<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\web\interfaces\IToArrayStrategy;
use Money\Currency;

class CurrencyObjectToArrayStrategy implements IToArrayStrategy
{
    /**
     * @param $property
     * @param Currency $value
     * @return array<string,string>
     */
    public function getArray($property, $value)
    {
        return [StringHelper::fromCamelCaseToUnderscore($property).'_name' => $value->getName()];
    }
}