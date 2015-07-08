<?php
namespace EuroMillions\vo;

class RememberToken extends ValueObject
{
    private $token;

    public function __construct($username, $password, $agentIdentificationString)
    {
        $this->assertNotEmpty($username);
        $this->assertNotEmpty($password);
        $this->assertNotEmpty($agentIdentificationString);
        $this->token = md5($username . $password . $agentIdentificationString);
    }

    public function token()
    {
        return $this->token;
    }
}