<?php

namespace EuroMillions\web\vo;

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

    public function getCardNumbers()
    {
        return $this->cardNumber->toNative();
    }

    public function getCVV()
    {
        return $this->cvv->toNative();
    }

    public function getHolderName()
    {
        return $this->cardHolderName->toNative();
    }
    
    public function getExpiryMonth()
    {
        return $this->expiryDate->getMonth();
    }

    public function getExpiryYear()
    {
        return $this->expiryDate()->getYear();
    }

}