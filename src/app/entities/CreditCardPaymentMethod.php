<?php

namespace EuroMillions\entities;

use EuroMillions\vo\CreditCard;

class CreditCardPaymentMethod extends PaymentMethod
{
    /** @var  CreditCard */
    private $creditCard;


    public function __construct(CreditCard $creditCard)
    {
        $this->creditCard = $creditCard;
        $this->cardHolderName = $creditCard->cardHolderName();
        $this->cardNumber = $creditCard->cardNumber();
        $this->cvv = $creditCard->cvv();
        $this->expiry_date = $creditCard->expiryDate();
        $this->company = $creditCard->getCompany();
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