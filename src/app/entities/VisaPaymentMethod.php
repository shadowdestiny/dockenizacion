<?php

namespace EuroMillions\entities;

use EuroMillions\vo\CreditCard;

class VisaPaymentMethod extends PaymentMethod
{
    /** @var  CreditCard */
    private $creditCard;
    /** @var  User */
    private $user;


    public function __construct(User $user, CreditCard $creditCard)
    {
        $this->user = $user;
        $this->creditCard = $creditCard;
    }

    public function creditCard()
    {
        return $this->creditCard;
    }
}