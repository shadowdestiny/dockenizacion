<?php
namespace EuroMillions\vo;

abstract class ValueObject
{
    protected function assertNotEmpty($string)
    {
        if (empty($string)) {
            throw new \InvalidArgumentException('Empty username');
        }
    }

    protected function assertNotTooShort($string)
    {
        if (strlen($string) < static::MIN_LENGTH) {
            throw new \InvalidArgumentException(sprintf(get_class($this).' must be %d characters or more', static::MIN_LENGTH));
        }
    }

    protected function assertNotTooLong($string)
    {
        if (strlen($string) > static::MAX_LENGTH) {
            throw new \InvalidArgumentException(sprintf(get_class($this).' must be %d characters or less', static::MAX_LENGTH));
        }
    }

    protected function assertValidFormat($string)
    {
        if (preg_match(static::FORMAT, $string) !== 1) {
            throw new \InvalidArgumentException('Invalid '.get_class($this).' format');
        }
    }

    protected function assertHasNumbers($string)
    {
        if (preg_match('/[0-9]/',$string) !== 1) {
            throw new \InvalidArgumentException(get_class($this).' must have numbers');
        }
    }
    protected function assertHasLowercaseChars($string)
    {
        if (preg_match('/[a-z]/',$string) !== 1) {
            throw new \InvalidArgumentException(get_class($this).' must have lowercase characters');
        }
    }
    protected function assertHasUppercaseChars($string)
    {
        if (preg_match('/[A-Z]/',$string) !== 1) {
            throw new \InvalidArgumentException(get_class($this).' must have uppercase characters');
        }
    }
}