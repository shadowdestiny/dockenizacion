<?php
namespace EuroMillions\interfaces;

use EuroMillions\entities\User;
use EuroMillions\vo\UserId;

interface IAuthStorageStrategy
{
    /**
     * @return UserId
     */
    public function getCurrentUserId();
    public function setCurrentUserId(UserId $userId);
    public function storeRemember(User $userId);
    public function removeCurrentUser();

    public function getRememberUserId();
    public function getRememberToken();
    public function removeRemember();
    public function hasRemember();

}