<?php
namespace EuroMillions\vo;

use EuroMillions\interfaces\IPasswordHasher;

class Password extends ValueObject
{
    private $password;
    private $passwordHasher;

    const MIN_LENGTH = 5;
    const MAX_LENGTH = 10;
    const FORMAT = '/^[a-zA-Z0-9_]+$/';

    public function __construct($password, IPasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->setPassword($password);
    }

    public function setPassword($password)
    {
        $this->assertNotEmpty($password);
        $this->assertNotTooShort($password);
        $this->assertValidFormat($password);
        $this->assertHasNumbers($password);
        $this->assertHasLowercaseChars($password);
        $this->assertHasUppercaseChars($password);
        $this->password = $this->passwordHasher->hashPassword($password);
    }

    public function password()
    {
        return $this->password;
    }
}