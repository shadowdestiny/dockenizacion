<?php
namespace EuroMillions\web\vo;

use Assert\Assertion;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\web\vo\base\StringLiteral;

class Password extends StringLiteral
{
    private $passwordHasher;

    const MIN_LENGTH = 5;
    const FORMAT = '/^[a-zA-Z0-9_]+$/';

    public function __construct($value, IPasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        Assertion::notEmpty($value);
        Assertion::minLength($value, self::MIN_LENGTH);
        Assertion::regex($value, self::FORMAT);
        parent::__construct($this->passwordHasher->hashPassword($value));
    }

    /**
     * @param Password $password
     * @return bool
     */
    public function equals(Password $password)
    {
        return $this->toNative() === $password->toNative();
    }
}
