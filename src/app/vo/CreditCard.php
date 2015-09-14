<?php

namespace EuroMillions\vo;

class CreditCard
{
    private $cardHolderName;

    private $cardNumber;

    private $expiryDate;

    private $cvv;


    public function __construct(CardHolderName $cardHolderName, CardNumber $cardNumber, ExpiryDate $expiryDate, CVV $cvv)
    {
        $this->cardHolderName = $cardHolderName;
        $this->cardNumber = $cardNumber;
        $this->expiryDate = $expiryDate;
        $this->cvv = $cvv;
    }

    public function cardHolderName()
    {
        return $this->cardHolderName;
    }

    public function cardNumber()
    {
        return $this->cardNumber;
    }

    public function expiryDate()
    {
        return $this->expiryDate;
    }

    public function cvv()
    {
        return $this->cvv;
    }

    public function getCompany()
    {
        return $this->cardNumber->type();
    }

    public function getLastNumbersOfCreditCard()
    {
        return substr($this->cardNumber->toNative(),-4);
    }

}