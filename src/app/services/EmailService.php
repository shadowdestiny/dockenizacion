<?php
namespace EuroMillions\services;

use EuroMillions\entities\User;
use EuroMillions\interfaces\IMailServiceApi;
use EuroMillions\vo\Password;
use EuroMillions\vo\Url;

class EmailService
{
    private $mailServiceApi;
    private $mailConfig;

    public function __construct(IMailServiceApi $mailServiceApi, $mailConfig)
    {
        $this->mailServiceApi = $mailServiceApi;
        $this->mailConfig = $mailConfig;
    }

    public function sendRegistrationMail(User $user, Url $url)
    {
        $this->sendMailToUser(
            $user,
            'Validate your email',
            'We need you to validate your email.',
            '<a href="' . $url->toNative() . '">Click here to validate you email!</a> <br>or copy and paste this url in your browser:<br>' . $url->toNative()
        );
    }

    public function sendPasswordResetMail(User $user, Url $url)
    {
        $this->sendMailToUser(
            $user,
            'Password reset',
            'Somebody has asked to reset your password.',
            'If it was you, you just have to <a href="'.$url->toNative().'">click this link to reset your password</a> <br>or copy and paste this url in your browser:<br>'.$url->toNative().'<br>If you didn\'t ask for the password reset, just ignore this email'
        );
    }

    public function sendNewPasswordMail(User $user, Password $password)
    {
        $this->sendMailToUser(
            $user,
            'New password',
            'We have created a new password for you:' . $password->toNative(),
            'Please use it.....'
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