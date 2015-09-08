<?php

namespace EuroMillions\entities;

use EuroMillions\interfaces\IPaymentMethod;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\CreditCard;

class VisaPaymentMethod implements IPaymentMethod
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

    public function charge($amount)
    {
        return new ServiceActionResult(true,'id transaction: xxxxxxxx');
    }

    public function creditCard()
    {
        return $this->creditCard;
    }
}