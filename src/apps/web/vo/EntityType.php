<?php


namespace EuroMillions\web\vo;


class EntityType
{
    public $entityType;
    private $data;

    public function __construct()
    {
        var_dump('se llama?');
    }

    public function __get($name)
    {
        if ($name !== 'data') {
            throw new \Exception('IncorrectField');
        }
        return $this->getData();
    }

    public function getData()
    {
        throw new \Exception('Use a child class, this is like an abstract class - It\'s not abstract for real because of Doctrine');
    }
}