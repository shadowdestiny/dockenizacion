<?php


namespace EuroMillions\entities;


use EuroMillions\interfaces\IEntity;

abstract class PaymentMethod extends EntityBase implements IEntity
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

    public function getId()
    {
        // TODO: Implement getId() method.
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

    }

}