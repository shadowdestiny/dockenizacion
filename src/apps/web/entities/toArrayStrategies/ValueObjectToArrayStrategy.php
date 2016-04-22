<?php
namespace EuroMillions\web\entities\toArrayStrategies;

use EuroMillions\web\interfaces\IToArrayStrategy;

class ValueObjectToArrayStrategy implements IToArrayStrategy
{
    /**
     * @param $property
     * @param $value
     * @return array
     */
    public function getArray($property, $value)
    {
        $vo_array = $value->toArray();
        foreach ($vo_array as $key => $item) {
            $result[$property.'_'.$key] = $item;
        }
        return $result;
    }
}