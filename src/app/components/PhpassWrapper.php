<?php
namespace EuroMillions\components;

use EuroMillions\interfaces\IPasswordHasher;
use Hautelook\Phpass\PasswordHash;

class PhpassWrapper implements IPasswordHasher
{
    /** @var PasswordHash */
    private $hasher;

    public function __construct()
    {
        $this->hasher = new PasswordHash(8, false);
    }

    public function hashPassword($password)
    {
        return $this->hasher->HashPassword($password);
    }

    public function checkPassword($password, $hash)
    {
        return $this->hasher->CheckPassword($password, $hash);
    }
}