<?php
namespace EuroMillions\services\preferences_strategies;

use EuroMillions\interfaces\ICurrencyStrategy;
use EuroMillions\interfaces\ISession;
use Money\Currency;

class WebCurrencyStrategy implements ICurrencyStrategy
{
    private $session;
    private $request;

    const CURRENCY_VAR = 'EM_currency';

    public function __construct(ISession $session)
    {
        $this->session = $session;
    }

    public function get()
    {
        if ($this->session->has(self::CURRENCY_VAR)) {
            return new Currency($this->session->get(self::CURRENCY_VAR));
        } else {
            return new Currency('EUR');
        }
    }

    public function set(Currency $currency)
    {
        $this->session->set(self::CURRENCY_VAR, $currency->getName());
    }
}