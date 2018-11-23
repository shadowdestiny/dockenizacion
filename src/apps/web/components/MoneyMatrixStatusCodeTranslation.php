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

    private $languageService;

    private $translation;

    private function __construct($key)
    {
        /** @var DomainServiceFactory $domainServiceFactory */
        $domainServiceFactory= \Phalcon\Di::getDefault()->get('domainServiceFactory');
        $this->languageService= $domainServiceFactory->getLanguageService();
        if($key == 'SUCCESS')
        {
            $this->translation = $this->languageService->translate('withdrawal_accepted');
        }
        if($key == 'REJECTED')
        {
            $this->translation = $this->languageService->translate('withdrawal_rejected');
        }
        if($key == 'CANCELED')
        {
            $this->translation = $this->languageService->translate('withdrawal_cancel');
        }
        if($key == 'PENDING_APPROVAL')
        {
            $this->translation = $this->languageService->translate('withdrawal_pending');
        }
    }

    public static function createStatusCodeTranslation($key)
    {
        return new static ($key);
    }

    /**
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

}