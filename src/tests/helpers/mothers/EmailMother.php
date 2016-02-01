<?php


namespace tests\helpers\mothers;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WelcomeEmailTemplate;
use EuroMillions\web\services\LotteriesDataService;

class EmailMother
{

    public static function aWelcomeEmailTemplate(LotteriesDataService $lotteriesDataService)
    {
        return new WelcomeEmailTemplate(self::initializedEmail(), $lotteriesDataService);
    }

    private static function initializedEmail()
    {
        return new EmailTemplate();
    }

}