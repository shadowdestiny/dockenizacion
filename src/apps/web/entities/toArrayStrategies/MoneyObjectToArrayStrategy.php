<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\shared\helpers\StringHelper;
use EuroMillions\web\interfaces\IToArrayStrategy;
use Money\Money;

class MoneyObjectToArrayStrategy implements IToArrayStrategy
{
    /**
     * @param $property
     * @param Money $value
     * @return array<string,string>
     */
    public function getArray($property, $value)
    {
        $prefix = StringHelper::fromCamelCaseToUnderscore($property);
        return [
            $prefix.'_currency_name' => $value->getCurrency()->getName(),
            $prefix.'_amount' => $value->getAmount()
        ];
    }
}