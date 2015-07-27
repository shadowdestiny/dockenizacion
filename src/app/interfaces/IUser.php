<?php
namespace EuroMillions\interfaces;

use EuroMillions\vo\UserId;

interface IUser
{
    public function setId(UserId $id);
    /** @return UserId */
    public function getId();
}