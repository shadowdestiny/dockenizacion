<?php
namespace EuroMillions\web\services\preferences_strategies;

use EuroMillions\shared\config\interfaces\ICookieManager;
use EuroMillions\web\interfaces\IUsersPreferencesStorageStrategy;
use EuroMillions\shared\config\interfaces\ISession;
use Money\Currency;
use Phalcon\Http\Cookie;

class WebUserPreferencesStorageStrategy implements IUsersPreferencesStorageStrategy
{
    private $session;
    private $cookieManager;

    const CURRENCY_VAR = 'EM_currency';

    public function __construct(ISession $session, ICookieManager $cookieManager)
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