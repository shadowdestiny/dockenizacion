<?php
namespace EuroMillions\web\vo;

use EuroMillions\web\interfaces\IEmailValidationToken;
use EuroMillions\web\vo\base\StringLiteral;


class ValidationToken extends StringLiteral
{
    public function __construct(Email $email, IEmailValidationToken $strategy)
    {
        $value = $strategy->token($email->toNative());
        parent::__construct($value);
    }
}