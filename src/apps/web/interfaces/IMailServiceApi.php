<?php
namespace EuroMillions\web\interfaces;

interface IMailServiceApi
{
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
    );

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
    );

}