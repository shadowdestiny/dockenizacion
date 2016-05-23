<?php


namespace EuroMillions\web\components;



use EuroMillions\web\interfaces\IMailServiceApi;
use Postmark\PostmarkClient;

class PostMarkWrapper implements IMailServiceApi
{

    private $postMark;

    public function __construct( $apiKey, PostmarkClient $postMark = null)
    {
        $this->postMark = $postMark ? $postMark : new PostmarkClient($apiKey);
    }

    public function sendPostMarkEmail(
        $templateId,
        $templateModel,
        $inlineCss = true,
        $from,
        $to,
        $cc = null,
        $bcc = null,
        $tag = null,
        $replyTo = null,
        $headers = null,
        $trackOpens = true,
        $attachments = null
    )
    {
        $this->postMark->sendEmailWithTemplate($from,$to,$templateId,$templateModel,$inlineCss,$tag,$trackOpens,$replyTo,$cc,$bcc,$headers,$attachments);
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
    )
    {
        // TODO: Implement send() method.
    }
}