<?php
namespace EuroMillions\web\vo;

use Assert\Assertion;
use EuroMillions\web\vo\base\StringLiteral;

class RememberToken extends StringLiteral
{
    public function __construct($username, $password, $agentIdentificationString)
    {
        Assertion::notEmpty($username);
        Assertion::notEmpty($password);
        Assertion::notEmpty($agentIdentificationString);
        parent::__construct(md5($username . $password . $agentIdentificationString));
    }
}