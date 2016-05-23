<?php
namespace EuroMillions\web\services;

use EuroMillions\shared\interfaces\IUrlManager;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\IEmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use EuroMillions\web\emailTemplates\WelcomeEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IMailServiceApi;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\vo\ContactFormInfo;
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

    public function sendWelcomeEmail(User $user, IUrlManager $urlManager)
    {
        //$url =  new Url($urlManager->get('/userAccess/passwordReset/' . $user->getValidationToken()));
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new WelcomeEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
        $emailTemplate->setUser($user);
        $this->sendTransactionalEmail($user, $emailTemplate);
    }

    public function sendLowBalanceEmail(User $user)
    {
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LowBalanceEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy());
        $this->sendTransactionalEmail($user,$emailTemplate);
    }

    public function sendRegistrationMail(User $user, Url $url)
    {
        $this->sendMailToUser(
            $user,
            'Validate your email',
            'We need you to validate your email to access our services.',
            'Thank you for registering an account with us. Please valudate the email address you regitered with by clicking on the link below:<br>
            <a href="' . $url->toNative() . '">Click this link to validate your registration</a>
            <br><br>or copy and paste this url in your browser:<br><span style="font-size:12px;">'.$url->toNative().'</span><br><br>If you didn\'t make the request, just ignore this email.',
            'Validate your email'
        );
    }

    public function sendPasswordResetMail(User $user, Url $url)
    {
        $this->sendMailToUser(
            $user,
            'Reset your password',
            'Generate a new password and reset the old one',
            'We have received a request to reset your password. If you didn\'t make the request, just ignore this email.<br>You can reset your password using this link: <a href="'.$url->toNative().'">Click here to reset your password</a>
                <br><br>or copy and paste this url in your browser: '.$url->toNative(),
            'Reset your password'
        );
    }

/* We shouldn't use anymore this code.

    public function sendNewPasswordMail(User $user, Password $password)
    {
        $this->sendMailToUser(
            $user,
            'Your New password',
            'Confirmation of creating a new password',
            'We have created a new password for you:<br><strong>'.$password->toNative().'</strong>
            <br><br>You can change and personalize your password later, by accessing "Change Password" in "Your Account" area.',
            'Your New password'
        );
    }
*/
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

    public function sendLog($name, $type, $message, $time)
    {
       // var_dump($name, $type, $message);die();
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
                'Error log',
                '',
                [
                    [
                        'name'    => 'title',
                        'content' => $name
                    ],
                    [
                        'name'    => 'subtitle',
                        'content' => $type
                    ],
                    [
                        'name'    => 'main',
                        'content' => $message,
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

    public function sendTransactionalEmail(User $user, IEmailTemplate $emailTemplate)
    {
        $this->sendTransactionalPostMark($user, $emailTemplate);
    }


    private function sendTransactional(User $user, IEmailTemplate $emailTemplate)
    {

        //$this->postMark->sendEmailWithTemplate($from,$to,$templateId,$templateModel,$inlineCss,$tag,$trackOpens,$replyTo,$cc,$bcc,$headers,$attachments);
        $vars = $emailTemplate->loadVars();
        $vars['vars'][] = $emailTemplate->loadHeader();
        $vars['vars'][] = $emailTemplate->loadFooter();

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
            $vars['vars'],
            [],
            $vars['template'],
            []
        );

    }

    private function sendTransactionalPostMark( User $user, IEmailTemplate $emailTemplate )
    {
//        $templateId,
//        $templateModel,
//        $inlineCss = true,
//        $from,
//        $to,
//        $cc = null,
//        $bcc = null,
//        $tag = null,
//        $replyTo = null,
//        $headers = null,
//        $trackOpens = true,
//        $attachments = null

        $vars = $emailTemplate->loadVars();
        $templateModel = $emailTemplate->loadVarsAsObject();
        $vars['vars'][] = $emailTemplate->loadHeader();
        $vars['vars'][] = $emailTemplate->loadFooter();
        $this->mailServiceApi->sendPostMarkEmail(
            $vars['template'],
            $templateModel,
            true,
            $this->mailConfig['from_address'],
            $user->getEmail()->toNative()
        );
    }

    /**
     * @param User $user
     * @param $title
     * @param $subtitle
     * @param $content
     */
    private function sendMailToUser(User $user, $title, $subtitle, $content, $subject = 'Confirm your email')
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
            $subject,
            '',
            [
                [
                    'name'    => 'simpletitle',
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
                        'email' => 'no-reply@euromillions.com',
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