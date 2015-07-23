<?php
namespace EuroMillions\interfaces;

interface ICurrentUserStrategy
{
    /**
     * @return IUser
     */
    public function getUser();
}