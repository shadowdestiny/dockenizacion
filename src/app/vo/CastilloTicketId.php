<?php


namespace EuroMillions\vo;


use Rhumsaa\Uuid\Uuid;

class CastilloTicketId
{

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public static function create()
    {
        return new static(Uuid::uuid4());
    }

    public function __toString()
    {
        return $this->id;
    }




}