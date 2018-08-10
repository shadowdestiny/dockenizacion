<?php

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IUser;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class OrderPaymentProviderDTO  extends DTOBase implements IDto
{
    public $MMdata;
    public $user;

    public function __construct(IUser $user)
    {
        /** @var User $user */
        $this->user = $user;
        $this->user->getId();
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->MMdata = $this->createDataMoneyMatrix();
    }

    public function toArray()
    {
        throw new \Exception('Method not implemented');
    }

    public function createDataMoneyMatrix()
    {
        return $this->MMdata = [
            "transactionID" => "123456",
            "userID" => $this->user->getId(),
            "firstName"=> $this->user->getName(),
            "lastName" =>  $this->user->getSurname(),
            "emailAddress" =>  $this->user->getEmail(),
            "countryCode" =>  $this->user->getCountry(),
            "CallbackUrl" =>  "http => //merchant-site.com/notifications.ashx",
            "ipAddress" =>  $this->user->getIpAddress(),
            "address" =>  $this->user->getStreet(),
            "city" =>  $this->user->getCity(),
            "phoneNumber" =>  $this->user->getPhoneNumber(),
            "postalCode" =>  $this->user->getZip(),
            "state" =>  "N/A",
            "birthDate" =>  "N/A",
            "paymentMethod" =>  "null",
            "amount" =>  "12.00",
            "currency" =>  "EUR",
            "SuccessUrl" =>  "http => //merchant-site.com/success.ashx",
            "FailUrl" =>  "http => //merchant-site.com/fail.ashx",
            "CancelUrl" =>  "http => //merchant-site.com/cancel.ashx",
            "CheckStatusUrl" =>  "http => //merchant-site.com/synch_check.ashx",
            "channel" =>  "Desktop",
            "allowPaySolChange" =>  "true",
            "registrationIpAddress" =>  "194.44.124.242",
            "registrationDate" =>  "10/10/2016",
            "merchantID" =>  "6"
        ];
    }

    public function setTransactionId()
    {

    }
}