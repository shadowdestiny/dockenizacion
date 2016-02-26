<?php


namespace EuroMillions\web\services\email_templates_strategies;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\CurrencyService;


class WinEmailAboveDataEmailTemplateStrategy implements IEmailTemplateDataStrategy
{

    protected $currencyService;
    public $user_currency;
    public $amount;

    public function __construct(CurrencyService $currencyService = null)
    {
        $this->currencyService = ($currencyService != null) ? $currencyService : \Phalcon\Di::getDefault()->get('domainServiceFactory')->getCurrencyService();
    }

    public function getData(IEmailTemplateDataStrategy $strategy = null)
    {
        try {
            $amount_converted = $this->currencyService->convert($this->amount,$this->user_currency);
            $amount_symbol = $this->currencyService->toString($amount_converted,$this->user_currency);
            return [
                'amount_converted' => $amount_symbol,
            ];
        } catch ( \Exception $e ) {

        }

    }
}