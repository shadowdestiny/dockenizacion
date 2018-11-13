<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 13/11/18
 * Time: 12:49
 */

namespace EuroMillions\web\components;


use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\vo\enum\MoneyMatrixStatusCode;

final class MoneyMatrixStatusCodeTranslation
{

    private static $languageService;

    private static  $moneyMatrixStatusCodeEnum;

    private function __construct()
    {
        /** @var DomainServiceFactory $domainServiceFactory */
        $domainServiceFactory = \Phalcon\Di::getDefault()->get('domainServiceFactory');
        $this->languageService = $domainServiceFactory->getLanguageService();
        $this->moneyMatrixStatusCodeEnum = new MoneyMatrixStatusCode();
    }


    public static function giveMeStatusTranslated($key)
    {
        self::$moneyMatrixStatusCodeEnum->getValue($key);
    }

}