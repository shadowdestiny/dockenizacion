<?php
namespace EuroMillions\shared\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\SiteConfig;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\external_apis\NullCurrencyApiCache;
use EuroMillions\web\services\external_apis\YahooCurrencyApi;
use Money\Currency;
use Money\Money;

class SiteConfigService
{
    /** @var  SiteConfig $configEntity */
    protected $configEntity;

    private $currencyService;

    public function __construct(EntityManager $entityManager, CurrencyService $currencyService = null)
    {
        $site_config_repository = $entityManager->getRepository('EuroMillions\web\entities\SiteConfig');

        $result = $entityManager->createQuery(
            "SELECT s from {$site_config_repository->getClassName()} s"
        )
            ->useResultCache(true, 300, 'SiteConfig')
            ->getResult();

        //EMTD Un-hardcode the expiration and id values. This config table may be used in the future for the configuration values of the different white labels.

        /** @var SiteConfig $config */
        $this->configEntity = $result[0];
        $this->currencyService = $currencyService ?: new CurrencyService(new YahooCurrencyApi(new NullCurrencyApiCache()), $entityManager);
        $this->currencyService = $currencyService;
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
        return $this->currencyService->convert($this->configEntity->getFee(), $user_currency);
    }

    public function getFeeToLimitValueWithCurrencyConverted( Currency $user_currency )
    {
        return $this->currencyService->convert($this->configEntity->getFeeToLimit(), $user_currency);
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
     * @param $locale
     * @return array
     */
    private function getCurrenciesVar(Currency $user_currency, $locale, Money $value)
    {
        $value_currency_convert = $this->currencyService->convert($value, $user_currency);
        $value = $this->currencyService->toString($value_currency_convert, $locale);
        return array($value);
    }


}