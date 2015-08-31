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
        $this->sendMailToUser(
            $user,
            'Validate your email',
            'We need you to validate your email.',
            '<a href="' . $url->toNative() . '">Click here to validate you email!</a> <br>or copy and paste this url in your browser:<br>' . $url->toNative()
        );
    }

    public function sendPasswordResetMail(User $user)
    {
        $url = $this->authService->getPasswordResetUrl($user);
        $this->sendMailToUser(
            $user,
            'Password reset',
            'Somebody has asked to reset your password.',
            'If it was you, you just have to <a href="'.$url->toNative().'">click this link to reset your password</a> <br>or copy and paste this url in your browser:<br>'.$url->toNative().'<br>If you didn\'t ask for the password reset, just ignore this email'
        );
    }

    /**
     * @param User $user
     * @param $title
     * @param $subtitle
     * @param $content
     */
    private function sendMailToUser(User $user, $title, $subtitle, $content)
    {
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
                    'name'    => 'title',
                    'content' => $title
                ],
                [
                    'name'    => 'subtitle',
                    'content' => $subtitle
                ],
                [
                    'name'    => 'main',
                    'content' => $content,
                ],
            ],
            [],
            'simple',
            []
        );
    }


}