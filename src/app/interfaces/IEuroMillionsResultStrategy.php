<?php
namespace EuroMillions\interfaces;

interface IEuroMillionsResultStrategy 
{
    /**
     * @param string $result;
     * @return array
     */
    public function load($result);
}