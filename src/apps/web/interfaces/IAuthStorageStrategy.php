<?php
namespace EuroMillions\web\interfaces;

use EuroMillions\web\entities\User;

interface IAuthStorageStrategy
{
    /** @return string */
    public function getCurrentUserId();

    /**
     * @param string $userId
     * @return void
     */
    public function setCurrentUserId($userId);

    /**
     * @param User $user
     * @return void
     */
    public function storeRemember(User $user);

    /**
     * @return void
     */
    public function removeCurrentUser();

    /**
     * @return string
     */
    public function getRememberUserId();

    /** @return string */
    public function getRememberToken();

    /** @return void */
    public function removeRemember();

    /** @return bool */
    public function hasRemember();

}