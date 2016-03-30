<?php
namespace EuroMillions\web\components;

use Ramsey\Uuid\Uuid;

class UserId
{
    public static function create()
    {
        return Uuid::uuid4();
    }

    public static function isValid($id)
    {
        return Uuid::isValid($id);
    }
}