<?php


namespace EuroMillions\interfaces;

use EuroMillions\entities\User;



interface IEMForm
{
    public function formToEntity(User $user,$json);
}