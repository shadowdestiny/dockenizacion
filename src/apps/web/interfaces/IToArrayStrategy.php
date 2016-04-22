<?php
namespace EuroMillions\web\interfaces;

interface IToArrayStrategy
{
    /**
     * @param $property
     * @param $value
     * @return array<string,string>
     */
    public function getArray($property, $value);
}