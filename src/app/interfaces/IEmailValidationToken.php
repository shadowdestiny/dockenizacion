<?php
namespace EuroMillions\interfaces;

interface IEmailValidationToken
{
    public function token($email);
    public function validate($email, $token);
}