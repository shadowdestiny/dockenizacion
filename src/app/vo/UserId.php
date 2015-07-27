<?php
namespace EuroMillions\vo;

use Rhumsaa\Uuid\Uuid;

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

    public static function create() {
        return new static(strtoupper(Uuid::uuid4()));
    }
}