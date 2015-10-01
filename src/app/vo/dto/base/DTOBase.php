<?php


namespace EuroMillions\vo\dto\base;


abstract class DTOBase
{

    abstract protected function exChangeObject();

    abstract public function toArray();

}