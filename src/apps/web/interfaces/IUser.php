<?php
namespace EuroMillions\web\interfaces;

use EuroMillions\web\vo\UserId;

interface IUser
{
    public function setId(UserId $id);
    /** @return UserId */
    public function getId();
}