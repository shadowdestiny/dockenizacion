<?php


namespace EuroMillions\entities;


use EuroMillions\interfaces\IEntity;

class PaymentMethod extends EntityBase implements IEntity
{

    protected $id;
    protected $user;
    protected $cardNumber;
    protected $cardHolderName;
    protected $expiry_date;
    protected $cvv;
    protected $type;
    protected $company;

    public function getType()
    {

    }

    public function getId()
    {
        // TODO: Implement getId() method.
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