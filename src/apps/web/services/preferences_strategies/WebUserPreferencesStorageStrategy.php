<?php
namespace EuroMillions\web\services\preferences_strategies;

use EuroMillions\shared\interfaces\ICookieManager;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use Money\Currency;
use Phalcon\Http\Cookie;
use Phalcon\Session\AdapterInterface;

class WebUserPreferencesStorageStrategy implements IUsersPreferencesStorageStrategy
{
    private $session;
    private $cookieManager;

    const CURRENCY_VAR = 'EM_currency';

    public function __construct(AdapterInterface $session, ICookieManager $cookieManager)
    {
        $this->session = $session;
        $this->cookieManager = $cookieManager;
    }

    public function getCurrency()
    {
        if ($this->session->has(self::CURRENCY_VAR)) {
            return new Currency($this->session->get(self::CURRENCY_VAR));
        } else {
            return new Currency('EUR');
        }
    }

    public function setCurrency(Currency $currency)
    {
        $this->session->set(self::CURRENCY_VAR, $currency->getName());
    }
}