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
        $this->assertHasNumbers($value);
        $this->assertHasLowercaseChars($value);
        $this->assertHasUppercaseChars($value);
        parent::__construct($this->passwordHasher->hashPassword($value));
    }

    private function assertHasNumbers($string)
    {
        if (preg_match('/[0-9]/', $string) !== 1) {
            throw new \InvalidArgumentException(get_class($this) . ' must have numbers');
        }
    }

    private function assertHasLowercaseChars($string)
    {
        if (preg_match('/[a-z]/', $string) !== 1) {
            throw new \InvalidArgumentException(get_class($this) . ' must have a lowercase letter');
        }
    }

    private function assertHasUppercaseChars($string)
    {
        if (preg_match('/[A-Z]/', $string) !== 1) {
            throw new \InvalidArgumentException(get_class($this) . ' must have an uppercase letter');
        }
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