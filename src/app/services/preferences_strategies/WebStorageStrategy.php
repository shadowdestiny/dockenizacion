<?php
namespace EuroMillions\services\preferences_strategies;

use EuroMillions\entities\GuestUser;
use EuroMillions\interfaces\ICookieManager;
use EuroMillions\interfaces\IStorageStrategy;
use EuroMillions\interfaces\ISession;
use EuroMillions\interfaces\IUser;
use EuroMillions\vo\UserId;
use Money\Currency;
use Phalcon\Http\Cookie;

class WebStorageStrategy implements IStorageStrategy
{
    private $session;
    private $cookieManager;

    const CURRENCY_VAR = 'EM_currency';
    const CURRENT_USER_VAR = 'EM_current_user';

    const GUEST_USER_EXPIRATION = 2592000; //(86400 * 30) = 30 days

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

    /**
     * @return IUser
     */
    public function getCurrentUser()
    {
        $user = $this->session->get(self::CURRENT_USER_VAR);
        if(!$user) {
            /** @var Cookie $cookie */
            $cookie = $this->cookieManager->get(self::CURRENT_USER_VAR);
            if (!$cookie) {
                $user_id = null;
            } else {
                $user_id = $cookie->getValue();
            }
            /** @var UserId $user_id */
            if (!$user_id) {
                $user_id = UserId::create();
            }
            $user = new GuestUser();
            $user->setId($user_id);
            $this->setCurrentUser($user);
        }
        return $user;
    }

    public function setCurrentUser(IUser $user)
    {
        $this->session->set(self::CURRENT_USER_VAR, $user);
        if (get_class($user) == 'EuroMillions\entities\GuestUser') {
            $this->cookieManager->set(self::CURRENT_USER_VAR, $user->getId(), self::GUEST_USER_EXPIRATION);
        }
    }
}