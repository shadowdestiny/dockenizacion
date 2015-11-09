<?php
namespace EuroMillions\web\services;

use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IMailServiceApi;
use EuroMillions\web\vo\ContactFormInfo;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\Url;

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
     * @param ContactFormInfo $contactFormInfo
     */
    public function sendContactRequest(ContactFormInfo $contactFormInfo)
    {
        $content = <<<EOF
        New contact request from: {$contactFormInfo->getFullName()} ({$contactFormInfo->getEmail()})
        <br>
        Content: {$contactFormInfo->getContent()}
EOF;
        $this->sendMailToContactService(
            'Contact request',
            $contactFormInfo->getTopic(),
            $content
        );
    }


    public function sendTransactionalEmail(User $user, $template, array $vars = null)
    {
        $this->sendTransactional($user, $template, $vars);
    }


    private function sendTransactional(User $user, $template, $vars = null)
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
            $vars['subject'],
            '',
            [],
            [],
            $template,
            []
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


    /**
     * @param $title
     * @param $subtitle
     * @param $content
     * @throws Exception
     * @throws \Exception
     */
    private function sendMailToContactService($title, $subtitle, $content)
    {
        try{
            $this->mailServiceApi->send(
                $this->mailConfig['from_name'],
                $this->mailConfig['from_address'],
                [
                    [
                        'email' => 'rmrbest@gmail.com',
                        'name'  => 'Contact',
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
        }catch(\Exception $e){
            throw $e;
        }
   }
}