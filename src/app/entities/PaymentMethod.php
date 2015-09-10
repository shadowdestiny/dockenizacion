<?php


namespace EuroMillions\entities;


use EuroMillions\interfaces\IEntity;

abstract class PaymentMethod extends EntityBase implements IEntity
{

    protected $id;
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

    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getUser()
    {

    }

    /**
     * @param $userID
     */
    public function setUser($userID)
    {
        $this->user=$userID;
    }

    public function getCardNumber()
    {

    }

    public function getCardHolderName()
    {

    }

    public function getExpiryDate()
    {

    }

    public function getCVV()
    {

    }

    public function getCompany()
    {

    }

}