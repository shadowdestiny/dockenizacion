<?php
namespace EuroMillions\vo;

use EuroMillions\interfaces\IEmailValidationToken;
use EuroMillions\vo\base\StringLiteral;


class ValidationToken extends StringLiteral
{
    public function __construct(Email $email, IEmailValidationToken $strategy)
    {
        $value = $strategy->token($email->toNative());
        parent::__construct($value);
    }
}