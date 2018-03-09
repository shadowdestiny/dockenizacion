<?php
namespace EuroMillions\web\services;

use Alcohol\ISO4217;
use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IJackpot;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use Money\Currency;
use Money\Money;

class UserPreferencesService
{
    private $storageStrategy;
    private $currencyConversionService;

    public function __construct(CurrencyConversionService $currencyConversionService, IUsersPreferencesStorageStrategy $storageStrategy)
    {
        $this->storageStrategy = $storageStrategy;
        $this->currencyConversionService = $currencyConversionService;
    }

    public function setCurrency(Currency $currency)
    {
        $this->storageStrategy->setCurrency($currency);
    }

    public function getCurrency()
    {
        $currency = $this->storageStrategy->getCurrency();
        if (!$currency) {
            $currency = new Currency('EUR');
        }
        return $currency;
    }

    public function setLanguage($language)
    {
        $this->storageStrategy->setLanguage($language);
    }

    public function getLanguage()
    {
        return $this->storageStrategy->getLanguage();
    }

    /**
     * @param IJackpot $jackpot
     * @param string $locale
     * @return string
     */
    public function getJackpotInMyCurrency(IJackpot $jackpot, $locale = 'en_US')
    {
        if ($jackpot->isValid()) {
            $amount = $this->currencyConversionService->convert(new Money((int)$jackpot->getAmount(), new Currency($jackpot->getCurrency())), $this->storageStrategy->getCurrency());
            $moneyFormatter = new MoneyFormatter();
            return $moneyFormatter->toStringByLocale($locale, $amount);
        } else {
            return $jackpot->getAmount();
        }
    }

    /**
     * @param IJackpot $jackpot
     * @param string $locale
     * @return string
     */
    public function getJackpotInMyCurrencyWithoutLetters(IJackpot $jackpot, $locale = 'en_US')
    {
        if ($jackpot->isValid()) {
            $amount = $this->currencyConversionService->convert(new Money((int)$jackpot->getAmount() / 1000000, new Currency($jackpot->getCurrency())), $this->storageStrategy->getCurrency());

            return $amount->getAmount();
        } else {
            return $jackpot->getAmount();
        }
    }

    /**
     * @param IJackpot $jackpot
     * @param string $locale
     * @return string
     */
    public function getJackpotInMyCurrencyAndMillions(IJackpot $jackpot, $locale = 'en_US')
    {
        if ($jackpot->isValid()) {
            $amount = $this->currencyConversionService->convert(new Money((int)$jackpot->getAmount() / 1000000, new Currency($jackpot->getCurrency())), $this->storageStrategy->getCurrency());
            $moneyFormatter = new MoneyFormatter();
            return $moneyFormatter->toStringByLocale($locale, $amount);
        } else {
            return $jackpot->getAmount();
        }
    }

    public function getMyCurrencyNameAndSymbol()
    {
        $currency = $this->getCurrency();
        $iso4217 = new ISO4217();
        $currency_data = $iso4217->getByAlpha3($currency->getName());
        $mf = new MoneyFormatter();
        $symbol = $mf->getSymbolFromCurrency('en_US', $currency);

        if (!$symbol)
        {
            $symbol = $currency->getName();
        }
        return ['symbol' => $symbol, 'name' => $currency_data['name']];
    }


}