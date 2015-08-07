<?php
namespace EuroMillions\services;

use EuroMillions\entities\User;
use EuroMillions\interfaces\IMailServiceApi;

class EmailService
{
    private $mailServiceApi;

    public function __construct(IMailServiceApi $mailServiceApi)
    {
        $this->mailServiceApi = $mailServiceApi;
    }

    public function sendRegistrationMail(User $user)
    {

    }


}