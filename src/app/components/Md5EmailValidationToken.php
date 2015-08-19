<?php
namespace EuroMillions\components;

use EuroMillions\interfaces\IEmailValidationToken;

class Md5EmailValidationToken implements IEmailValidationToken
{
    const SECRET_KEY = 'LÑJADSÑ3IO8H“”¥ß  “¬÷';

    public function token($email)
    {
        return md5(self::SECRET_KEY . $email);
    }

    public function validate($email, $token)
    {
        return $token == $this->token($email);
    }
}