<?php
namespace EuroMillions\services;

use EuroMillions\entities\User;
use EuroMillions\interfaces\IMailServiceApi;

class EmailService
{
    private $mailServiceApi;
    private $authService;
    private $mailConfig;

    public function __construct(IMailServiceApi $mailServiceApi, AuthService $authService, $mailConfig)
    {
        $this->mailServiceApi = $mailServiceApi;
        $this->authService = $authService;
        $this->mailConfig = $mailConfig;
    }

    public function sendRegistrationMail(User $user)
    {
        $url = $this->authService->getValidationUrl($user);
        $this->mailServiceApi->send(
            $this->mailConfig['from_name'],
            $this->mailConfig['from_address'],
            [
                [
                    'email' => $user->getEmail()->toNative(),
                    'name'  => $user->getSurname() . ', ' . $user->getName(),
                    'type'  => 'to',
                ]

            ],
            'Confirm your email',
            '',
            [
                [
                    'name' => 'title',
                    'content' => 'Validate your email'
                ],
                [
                    'name' => 'subtitle',
                    'content' => 'We need you to validate your email.'
                ],
                [
                    'name' => 'main',
                    'content' => '<a href="'.$url->toNative().'">Click here to validate you email!</a> <br>or copy and paste this url in your browser:<br>'.$url->toNative(),
                ],
            ],
            [],
            'simple',
            []
        );
    }


}