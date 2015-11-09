<?php
namespace EuroMillions\web\interfaces;

interface IEmailValidationToken
{
    public function token($email);
    public function validate($email, $token);
}