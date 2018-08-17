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
    public $totalPrice;
    public $currency;
    public $lottery;
    public $transactionID;


    public function __construct(IUser $user, $total_price, $currency, $lottery)
    {
        /** @var User $user */
        $this->user = $user;
        $this->totalPrice = $total_price;
        $this->currency = $currency;
        $this->lottery = $lottery;
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

    protected function createDataMoneyMatrix()
    {
        return $this->MMdata = [
            "transactionID" => $this->transactionID,
            "userID" => $this->user->getId(),
            "firstName" => $this->user->getName(),
            "lastName" => $this->user->getSurname(),
            "emailAddress" => $this->user->getEmail(),
            "countryCode" => $this->user->getCountry(),
            "CallbackUrl" => "http => //merchant-site.com/notifications.ashx",
            "ipAddress" => $this->user->getIpAddress(),
            "address" => $this->user->getStreet(),
            "city" => $this->user->getCity(),
            "phoneNumber" => $this->user->getPhoneNumber(),
            "postalCode" => $this->user->getZip(),
            "state" => "N/A",
            "birthDate" => "N/A",
            "paymentMethod" => "null",
            "amount" => $this->totalPrice / 100,
            "currency" => $this->currency,
            "SuccessUrl" => "https://localhost:4433/" . $this->lottery . "/payment/payment?method=wallet",
            "FailUrl" => "http => //merchant-site.com/fail.ashx",
            "CancelUrl" => "http => //merchant-site.com/cancel.ashx",
            "CheckStatusUrl" => "http => //merchant-site.com/synch_check.ashx",
            "channel" => "Desktop",
            "allowPaySolChange" => "true",
            "registrationIpAddress" => $this->user->getIpAddress(),
            "registrationDate" => $this->user->getLastConnection()
        ];
    }

    /**
     * @return mixed
     */
    public function getTransactionID()
    {
        return $this->transactionID;
    }

    /**
     * @param mixed $transactionID
     */
    public function setTransactionID($transactionID)
    {
        $this->transactionID = $transactionID;
    }

    public function toJson()
    {
        return json_encode($this->MMdata);
    }

}