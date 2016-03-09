<?php
namespace EuroMillions\shared\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\SiteConfig;
use EuroMillions\web\repositories\SiteConfigRepository;
use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;

class SiteConfigService
{
    /** @var  SiteConfig $configEntity */
    protected $configEntity;

    private $currencyConversionService;

    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService)
    {
        /** @var SiteConfigRepository $site_config_repository */
        $site_config_repository = $entityManager->getRepository('EuroMillions\web\entities\SiteConfig');

        $result = $site_config_repository->getSiteConfig();

        /** @var SiteConfig $config */
        $this->configEntity = $result[0];
        $this->currencyConversionService = $currencyConversionService;
    }

    /**
     * @return Money
     */
    public function getFee()
    {
        return $this->configEntity->getFee();
    }

    public function getFeeValueWithCurrencyConverted( Currency $user_currency )
    {
        return $this->currencyConversionService->convert($this->configEntity->getFee(), $user_currency);
    }

    public function getFeeToLimitValueWithCurrencyConverted( Currency $user_currency )
    {
        return $this->currencyConversionService->convert($this->configEntity->getFeeToLimit(), $user_currency);
    }

    /**
     * @return Money
     */
    public function getFeeToLimitValue()
    {
        return $this->configEntity->getFeeToLimit();
    }

    public function getFeeFormatMoney( Currency $user_currency, $locale)
    {
        list($value) = $this->getCurrenciesVar($user_currency, $locale,  $this->configEntity->getFee());
        return $value;
    }

    public function getFeeLimitFormatMoney( Currency $user_currency, $locale)
    {
        list($value) = $this->getCurrenciesVar($user_currency, $locale,  $this->configEntity->getFeeToLimit());
        return $value;
    }

    /**
     * @param Currency $user_currency
     * @param string $locale
     * @param Money $value
     * @return string[]
     */
    private function getCurrenciesVar(Currency $user_currency, $locale, Money $value)
    {
        $value_currency_convert = $this->currencyConversionService->convert($value, $user_currency);
        $value = $this->currencyConversionService->toString($value_currency_convert, $locale);
        return array($value);
    }


}