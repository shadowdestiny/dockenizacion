<?php


namespace EuroMillions\services;


use EuroMillions\entities\User;

class PaymentProcessService
{

    /** @var  User */
    private $user;

    private $bet;

    public function __construct(User $user, Bet $bet)
    {
        $this->user=$user;
        $this->bet=$bet;
    }


    public function callPaymentMethod()
    {

    }
}