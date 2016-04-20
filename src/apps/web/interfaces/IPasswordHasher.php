<?php
namespace EuroMillions\web\interfaces;

interface IPasswordHasher
{
    public function hashPassword($password);

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public function checkPassword($password, $hash);
}