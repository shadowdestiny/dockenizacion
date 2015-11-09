<?php
namespace EuroMillions\web\interfaces;

use EuroMillions\web\entities\User;
use EuroMillions\web\vo\UserId;

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