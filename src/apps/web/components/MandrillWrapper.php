<?php
namespace EuroMillions\web\components;

use EuroMillions\web\interfaces\IMailServiceApi;
use Mandrill;

class MandrillWrapper implements IMailServiceApi
{
    private $mandrill;

    public function __construct($apiKey, Mandrill $mandrill = null)
    {
        $this->mandrill = $mandrill ? $mandrill : new Mandrill($apiKey);
    }

    public function send(
        $fromName,
        $fromEmail,
        array $to,
        $subject,
        $html,
        array $globalVars,
        array $recipientVars,
        $templateName = null,
        array $templateVars = null
    ) {
        $message = [
            'html'                => $html,
            'subject'             => $subject,
            'from_email'          => $fromEmail,
            'from_name'           => $fromName,
            'to'                  => $to,
            'important'           => true,
            'track_opens'         => true,
            'track_clicks'        => true,
            'auto_text'           => true,
            'preserve_recipients' => false,
            'global_merge_vars'   => $globalVars,
            'merge_vars'          => $recipientVars,
        ];
        if ($templateName) {
            //print_r(json_encode($message));die();
            $this->mandrill->messages->sendTemplate($templateName, $templateVars, $message);
        } else {
            $this->mandrill->messages->send($message);
        }
    }

    public function sendPostMarkEmail(
        $templateId,
        $templateModel,
        $inlineCss = null,
        $from,
        $to,
        $cc = null,
        $bcc = null,
        $tag = null,
        $replyTo = null,
        $headers = null,
        $trackOpens = null,
        $attachments = null
    )
    {
        // TODO: Implement sendPostMarkEmail() method.
    }
}