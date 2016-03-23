<?php
namespace EuroMillions\web\components;

use Ramsey\Uuid\Uuid;

class UserId
{
    public static function create()
    {
        return Uuid::uuid4();
    }
}