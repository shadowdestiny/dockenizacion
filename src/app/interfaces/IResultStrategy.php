<?php
namespace EuroMillions\interfaces;

interface IResultStrategy
{
    /**
     * @param string $result;
     * @return array
     */
    public function load($result);
}