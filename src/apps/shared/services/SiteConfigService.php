<?php
namespace EuroMillions\shared\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\SiteConfig;
use EuroMillions\web\repositories\SiteConfigRepository;
use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency as MoneyCurrency;
use EuroMillions\web\vo\dto\SiteConfigDTO;
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

        if (count($result)) {
            $this->configEntity = $result[0];
        } else {
            $this->configEntity = new SiteConfig();
            $this->configEntity->initialize([
                'id' => '1',
                'fee' => new Money(35, new MoneyCurrency('EUR')),
                'fee_to_limit' => new Money(1200, new MoneyCurrency('EUR')),
                'default_currency' => new MoneyCurrency('EUR'),
            ]); //EMTD esto tendrá que ser sacado a un fichero de configuración más adelante (sobre todo al llegar a marcas blancas)
        }
        $this->currencyConversionService = $currencyConversionService;
    }

    /**
     * @return Money
     */
    public function getFee()
    {
        return $this->configEntity->getFee();
    }

    public function getFeeValueWithCurrencyConverted( MoneyCurrency $user_currency )
    {
        return $this->currencyConversionService->convert($this->configEntity->getFee(), $user_currency);
    }

    public function getFeeToLimitValueWithCurrencyConverted( MoneyCurrency $user_currency )
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

    public function getFeeFormatMoney( MoneyCurrency $user_currency, $locale)
    {
        list($value) = $this->getCurrenciesVar($user_currency, $locale,  $this->configEntity->getFee());
        return $value;
    }

    public function getFeeLimitFormatMoney( MoneyCurrency $user_currency, $locale)
    {
        list($value) = $this->getCurrenciesVar($user_currency, $locale,  $this->configEntity->getFeeToLimit());
        return $value;
    }

    public function getSiteConfigDTO( MoneyCurrency $currency, $locale )
    {
        $fee_to_limit_convert = $this->currencyConversionService->convert($this->configEntity->getFeeToLimit(), $currency);
        $amount_fee_to_limit = $this->currencyConversionService->toString($fee_to_limit_convert , $locale);
        $fee_convert = $this->currencyConversionService->convert($this->configEntity->getFee(), $currency);
        $amount_fee = $this->currencyConversionService->toString($fee_convert, $locale);
        return new SiteConfigDTO($amount_fee_to_limit, $amount_fee, $fee_to_limit_convert->getAmount(), $fee_convert->getAmount());
    }


    /**
     * @param MoneyCurrency $user_currency
     * @param string $locale
     * @param Money $value
     * @return string[]
     */
    private function getCurrenciesVar(MoneyCurrency $user_currency, $locale, Money $value)
    {
        $value_currency_convert = $this->currencyConversionService->convert($value, $user_currency);
        $value = $this->currencyConversionService->toString($value_currency_convert, $locale);
        return array($value);
    }


}