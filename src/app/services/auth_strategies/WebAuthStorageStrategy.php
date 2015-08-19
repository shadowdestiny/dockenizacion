<?php
namespace EuroMillions\services\auth_strategies;

use EuroMillions\entities\User;
use EuroMillions\interfaces\IAuthStorageStrategy;
use EuroMillions\interfaces\ICookieManager;
use EuroMillions\interfaces\ISession;
use EuroMillions\vo\UserId;
use Phalcon\Http\Cookie;

class WebAuthStorageStrategy implements IAuthStorageStrategy
{
    const CURRENT_USER_VAR = 'EM_current_user';
    const GUEST_USER_EXPIRATION = 2592000; //(86400 * 30) = 30 days
    const REMEMBER_ME_EXPIRATION = 691200; //(86400 * 8) = 8 days
    const REMEMBER_USERID_VAR = 'EM_RMU';
    const REMEMBER_TOKEN_VAR = 'EM_RMT';

    private $session;
    private $cookieManager;

    public function __construct(ISession $session, ICookieManager $cookieManager)
    {
        $this->session = $session;
        $this->cookieManager = $cookieManager;
    }

    /**
     * @return UserId
     */
    public function getCurrentUserId()
    {
        $id = $this->session->get(self::CURRENT_USER_VAR);
        if(!$id) {
            /** @var Cookie $cookie */
            $cookie = $this->cookieManager->get(self::CURRENT_USER_VAR);
            if (!$cookie) {
                $id = null;
            } else {
                $id = $cookie->getValue();
            }
            if (!$id) {
                $user_id = UserId::create();
            } else {
                $user_id = new UserId($id);
            }
            $this->setCurrentUserId($user_id);
        } else {
            $user_id = new UserId($id);
        }
        return $user_id;
    }

    public function setCurrentUserId(UserId $userId)
    {
        $this->session->set(self::CURRENT_USER_VAR, $userId->id());
        $this->cookieManager->set(self::CURRENT_USER_VAR, $userId->id(), self::GUEST_USER_EXPIRATION);
    }

    public function removeCurrentUser()
    {
        $this->session->destroy();
    }

    public function storeRemember(User $user)
    {
        $this->cookieManager->set(self::REMEMBER_USERID_VAR, $user->getId()->id(), self::REMEMBER_ME_EXPIRATION);
        $this->cookieManager->set(self::REMEMBER_TOKEN_VAR, $user->getRememberToken()->token(), self::REMEMBER_ME_EXPIRATION);
    }

    public function getRememberUserId()
    {
        return $this->cookieManager->get(self::REMEMBER_USERID_VAR);
    }

    public function getRememberToken()
    {
        return $this->cookieManager->get(self::REMEMBER_TOKEN_VAR);
    }

    public function removeRemember()
    {
        $this->cookieManager->delete(self::REMEMBER_USERID_VAR);
        $this->cookieManager->delete(self::REMEMBER_TOKEN_VAR);
    }

    public function hasRemember()
    {
        $this->cookieManager->has(self::REMEMBER_USERID_VAR);
    }
}