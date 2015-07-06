<?php
namespace EuroMillions\vo;

class UserId 
{
    private $id;

    public function __construct($anId)
    {
        $this->id = $anId;
    }

    public function id() {
        return $this->id;
    }

    public static function create($anId) {
        return new static($anId);
    }
}