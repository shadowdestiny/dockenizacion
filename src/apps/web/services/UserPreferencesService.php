<?php
namespace EuroMillions\web\services;

use Alcohol\ISO4217;
use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use Money\Currency;
use Money\Money;

class UserPreferencesService
{
    private $storageStrategy;
    private $currencyService;

    public function __construct(CurrencyService $currencyService, IUsersPreferencesStorageStrategy $storageStrategy)
    {
        $this->storageStrategy = $storageStrategy;
        $this->currencyService = $currencyService;
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

    /**
     * @param Money $jackpot
     * @return Money
     */
    public function getJackpotInMyCurrency(Money $jackpot)
    {
        return $this->currencyService->convert($jackpot, $this->storageStrategy->getCurrency());
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