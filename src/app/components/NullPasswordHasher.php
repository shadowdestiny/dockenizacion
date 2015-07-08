<?php


namespace EuroMillions\components;


use EuroMillions\interfaces\IPasswordHasher;

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