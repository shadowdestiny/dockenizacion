<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\CurrencyConversionService;
use Money\Currency;
use Money\Money;


class WinEmailAboveDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{
    /** @var  CurrencyConversionService */
    protected $currencyConversionService;
    private $userCurrency;
    private $amount;

    public function __construct(Money $amount, Currency $userCurrency, CurrencyConversionService $currencyConversionService = null)
    {
        $this->amount = $amount;
        $this->userCurrency = $userCurrency;
        $this->currencyConversionService = $currencyConversionService ?: \Phalcon\Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {
            $amount_converted = $this->currencyConversionService->convert($this->amount,$this->userCurrency);
            $amount_symbol = $this->currencyConversionService->toString($amount_converted,$this->userCurrency);
            return [
                'amount_converted' => $amount_symbol,
            ];
        } catch ( \Exception $e ) {

        }

    }
}