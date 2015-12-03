<?php
namespace EuroMillions\web\services;

use antonienko\MoneyFormatter\MoneyFormatter;
use Doctrine\ORM\EntityManager;
use EuroMillions\web\interfaces\ICurrencyApi;
use EuroMillions\web\repositories\CurrencyRepository;
use EuroMillions\web\vo\ActionResult;
use Money\Currency;
use Money\Money;

class CurrencyService
{

    private $currencyApi;

    /** @var CurrencyRepository */
    private $currencyRepository;

    public function __construct(ICurrencyApi $currencyApi, EntityManager $entityManager)
    {
        $this->currencyApi = $currencyApi;
        $this->currencyRepository = $entityManager->getRepository('EuroMillions\web\entities\Currency');
    }

    public function convert(Money $from,Currency $to)
    {
        if( $from->getCurrency()->equals($to) ) {
            return $from;
        }
        $currency_pair = $this->currencyApi->getRate($from->getCurrency(), $to);
        return $currency_pair->convert($from);
    }

    public function toString(Money $money, $locale)
    {
        $money_formatter = new MoneyFormatter();
        return $money_formatter->toStringByLocale($locale, $money);
    }

    public function getSymbol(Money $money, $locale)
    {
        $money_formatter = new MoneyFormatter();
        return $money_formatter->getSymbol($locale, $money);
    }

    public function getActiveCurrenciesCodeAndNames()
    {
        /** @var Currency $collection */
        $collection = $this->currencyRepository->findAll();
        if(!empty($collection)) {
            return new ActionResult(true,$collection);
        } else {
            return new ActionResult(false);
        }
        //EMTD
//        return [
//            ['symbol' => '€', 'code' => 'EUR', 'name' => 'Euro'],
//            ['symbol' => '$', 'code' => 'USD', 'name' => 'US Dollar'],
//            ['symbol' => 'COP', 'code' => 'COP', 'name' => 'Colombian Peso'],
//            ['symbol' => '&pound;', 'code' => 'GBP', 'name' => 'Pound Sterling'],
//            ['symbol' => '&#x20bd;', 'code' => 'RUR', 'name' => 'Russian Ruble'],
//            ['symbol' => '&#x43;&#x48;&#x46;', 'code' => 'CHF', 'name' => 'Swiss Franc'],
//            ['symbol' => '&#x41;&#x24;', 'code' => 'AUD', 'name' => 'Australian Dolar'],
//            ['symbol' => 'lei', 'code' => 'RON', 'name' => 'Romanian Leu'],
//            ['symbol' => '&#1083;&#1074;', 'code' => 'BGN', 'name' => 'Bulgarian Lev'],
//            ['symbol' => '&#82;', 'code' => 'ZAR', 'name' => 'South African Rand'],
//            ['symbol' => '&#107;&#114;', 'code' => 'SEK', 'name' => 'Swedish Krone'],
//            ['symbol' => '&#107;&#114;', 'code' => 'DKK', 'name' => 'Danish Krone'],
//            ['symbol' => '&#8377;', 'code' => 'INR', 'name' => 'Indian Rupee'],
//            ['symbol' => '&#x20bd;', 'code' => 'BLR', 'name' => 'Belarusian Ruble'],
//            ['symbol' => '&#36;', 'code' => 'CAD', 'name' => 'Canadian Dollar'],
//            ['symbol' => '&#165;', 'code' => 'CNY', 'name' => 'Chinese Yuan'],
//            ['symbol' => '&#165;', 'code' => 'JPY', 'name' => 'Japanese Yen'],
//            ['symbol' => '&#3647;', 'code' => 'THB', 'name' => 'Thai Baht'],
//        ];
    }

}