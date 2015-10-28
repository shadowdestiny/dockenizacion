<?php


namespace EuroMillions\web\components;


use EuroMillions\web\interfaces\IPasswordHasher;

class NullPasswordHasher implements IPasswordHasher
{

    public function hashPassword($password)
    {
        return $password;
    }

    public function checkPassword($password, $hash)
    {
        return $password == $hash;
    }
}