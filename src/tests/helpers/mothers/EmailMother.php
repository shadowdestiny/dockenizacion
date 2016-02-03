<?php


namespace tests\helpers\mothers;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\ResetPasswordEmailTemplate;
use EuroMillions\web\emailTemplates\WelcomeEmailTemplate;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\services\LotteriesDataService;

class EmailMother
{

    public static function aWelcomeEmailTemplate()
    {
        return new WelcomeEmailTemplate(self::initializedEmail(), new NullEmailTemplateDataStrategy());
    }

    public static function aResetPasswordEmailTemplate()
    {
        return new ResetPasswordEmailTemplate(self::initializedEmail(), new NullEmailTemplateDataStrategy());
    }

    private static function initializedEmail()
    {
        return new EmailTemplate();
    }

}