<?php
namespace EuroMillions\web\interfaces;

interface IPasswordHasher
{
    public function hashPassword($password);

    public function checkPassword($password, $hash);
}