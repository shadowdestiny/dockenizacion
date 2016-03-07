<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\CurrencyConversionService;


class WinEmailAboveDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{
    /** @var  CurrencyConversionService */
    protected $currencyConversionService;
    public $user_currency;
    public $amount;

    public function __construct(CurrencyConversionService $currencyConversionService = null)
    {
        $this->currencyConversionService = $currencyConversionService ?: \Phalcon\Di::getDefault()->get('domainServiceFactory')->getCurrencyConversionService();
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {
            $amount_converted = $this->currencyConversionService->convert($this->amount,$this->user_currency);
            $amount_symbol = $this->currencyConversionService->toString($amount_converted,$this->user_currency);
            return [
                'amount_converted' => $amount_symbol,
            ];
        } catch ( \Exception $e ) {

        }

    }
}