<?php
namespace EuroMillions\interfaces;

interface IPasswordHasher
{
    public function hashPassword($password);

    public function checkPassword($password, $hash);
}