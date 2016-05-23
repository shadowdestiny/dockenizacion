<?php


namespace EuroMillions\web\interfaces;


interface IMailServicePostMarkAPI
{

    public function sendEmailFromPostMark(
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
}