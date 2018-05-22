<?php


namespace EuroMillions\web\interfaces;

use EuroMillions\web\entities\User;



interface IEMForm
{
    public function formToEntity(User $user,$json, $bet);
}