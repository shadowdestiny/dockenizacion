<?php

namespace EuroMillions\vo;

class Username extends ValueObject
{
    const MIN_LENGTH = 5;
    const MAX_LENGTH = 10;
    const FORMAT = '/^[a-zA-Z0-9_]+$/';

    private $username;

    public function __construct($username)
    {
        $this->setUsername($username);
    }

    private function setUsername($username)
    {
        $this->assertNotEmpty($username);
        $this->assertNotTooShort($username);
        $this->assertNotTooLong($username);
        $this->assertValidFormat($username);
        $this->username = $username;
    }

    public function username()
    {
        return $this->username;
    }

}

