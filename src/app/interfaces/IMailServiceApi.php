<?php
namespace EuroMillions\interfaces;

interface IMailServiceApi
{
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