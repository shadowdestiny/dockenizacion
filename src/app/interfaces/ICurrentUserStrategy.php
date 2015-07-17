<?php
namespace EuroMillions\interfaces;

use EuroMillions\entities\User;

interface ICurrentUserStrategy
{
    /**
     * @return User
     */
    public function getUser();
}