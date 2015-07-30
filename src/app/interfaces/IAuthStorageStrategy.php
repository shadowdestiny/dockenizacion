<?php
namespace EuroMillions\interfaces;

use EuroMillions\entities\User;

interface IAuthStorageStrategy
{
    /**
     * @return IUser
     */
    public function getCurrentUser();
    public function setCurrentUser(IUser $user);
    public function storeRemember(User $user);

    public function getRememberUserId();
    public function getRememberToken();
    public function removeRemember();
    public function hasRemember();

}