<?php

namespace EuroMillions\entities;

use EuroMillions\vo\CreditCard;

class CreditCardPaymentMethod extends PaymentMethod
{
    /** @var  CreditCard */
    private $creditCard;
    /** @var  User */
    protected $user;


    public function __construct(User $user, CreditCard $creditCard)
    {
        $this->user = $user;
        $this->creditCard = $creditCard;
    }

    public function creditCard()
    {
        return $this->creditCard;
    }

    public function getType()
    {
        return $this->creditCard()->getCompany();
    }

    public function getId()
    {

    }
}