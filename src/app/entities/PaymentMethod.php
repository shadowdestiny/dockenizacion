<?php


namespace EuroMillions\entities;

abstract class PaymentMethod extends EntityBase
{

    protected $id;
    /** @var  User */
    protected $user;
    protected $cardNumber;
    protected $cardHolderName;
    protected $expiry_date;
    protected $cvv;
    protected $payment_method_type;
    protected $company;
    protected $type;

    public function getPaymentMethodType()
    {
        return $this->type;
    }

    public function setId($id)
    {
        $this->id=$id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user=$user;
    }

    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $cardNumber
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param $cardHolderName
     */
    public function setCardHolderName($cardHolderName)
    {
        $this->cardHolderName=$cardHolderName;
    }

    public function getCardHolderName()
    {
        return $this->cardHolderName;
    }

    /**
     * @param $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiry_date=$expiryDate;
    }

    public function getExpiryDate()
    {
        return $this->expiry_date;
    }

    /**
     * @param $cvv
     */
    public function setCVV($cvv)
    {
        $this->cvv=$cvv;
    }

    public function getCVV()
    {
        return $this->cvv;
    }

    /**
     * @param $company
     */
    public function setCompany($company)
    {
        $this->company=$company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setPaymentMethodType($payment_method_type)
    {
        $this->payment_method_type = $payment_method_type;
    }
}