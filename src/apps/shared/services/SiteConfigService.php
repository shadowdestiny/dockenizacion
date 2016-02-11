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
            ->useResultCache(true)
            ->getResult();

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
        list($value_currency_convert, $symbol_position, $symbol) = $this->getCurrenciesVar($user_currency, $locale,  $this->configEntity->getFee());
        return ($symbol_position) ? $value_currency_convert->getAmount() / 100 . ' ' . $symbol : $symbol . ' ' . $value_currency_convert->getAmount() / 100;
    }

    public function getFeeLimitFormatMoney( Currency $user_currency, $locale)
    {
        list($value_currency_convert, $symbol_position, $symbol) = $this->getCurrenciesVar($user_currency, $locale,  $this->configEntity->getFeeToLimit());
        return ($symbol_position) ? $value_currency_convert->getAmount() / 1000 . ' ' . $symbol : $symbol . ' ' . $value_currency_convert->getAmount() / 1000;
    }

    /**
     * @param Currency $user_currency
     * @param $locale
     * @return array
     */
    private function getCurrenciesVar(Currency $user_currency, $locale, Money $value)
    {
        $value_currency_convert = $this->currencyService->convert($value, $user_currency);
        $symbol_position = $this->currencyService->getSymbolPosition($locale, $user_currency);
        $symbol = $this->currencyService->getSymbol($value, $locale);
        return array($value_currency_convert, $symbol_position, $symbol);
    }


}