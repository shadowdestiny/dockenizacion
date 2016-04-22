<?php
namespace EuroMillions\web\interfaces;

interface IToArrayStrategy
{
    /**
     * @param $property
     * @param $value
     * @return array
     */
    public function getArray($property, $value);
}