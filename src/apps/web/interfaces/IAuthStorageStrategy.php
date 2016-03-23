<?php
namespace EuroMillions\web\interfaces;

use EuroMillions\web\entities\User;

interface IAuthStorageStrategy
{
    public function getCurrentUserId();
    public function setCurrentUserId($userId);
    public function storeRemember(User $user);
    public function removeCurrentUser();

    public function getRememberUserId();
    public function getRememberToken();
    public function removeRemember();
    public function hasRemember();

}