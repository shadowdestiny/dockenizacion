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
    public $isWallet;


    public function __construct(IUser $user, $total_price, $currency, $lottery, $isWallet = false)
    {
        /** @var User $user */
        $this->user = $user;
        $this->totalPrice = $total_price;
        $this->currency = $currency;
        $this->lottery = $lottery;
        $this->isWallet = $isWallet;
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
            "orderID" => $this->getTransactionID(),
            "userID" => $this->user->getId(),
            "firstName" => $this->user->getName(),
            "lastName" => $this->user->getSurname(),
            "emailAddress" => $this->user->getEmail()->toNative(),
            "countryCode" => "ES",
            "CallbackUrl" => "https://rancher-beta.euromillions.com:49167/notification",
            "ipAddress" => $this->user->getIpAddress()->toNative(),
            "address" => $this->user->getStreet() == null ? "" : $this->user->getStreet(),
            "city" => $this->user->getCity() == null ? "" : $this->user->getCity(),
            "phoneNumber" => $this->user->getPhoneNumber() == null ? "" : $this->user->getPhoneNumber(),
            "postalCode" => $this->user->getZip() == null ? "" : $this->user->getZip(),
            "state" => "",
            "birthDate" => "",
            "paymentMethod" => "null",
            "amount" =>  number_format($this->totalPrice / 100,2),
            "currency" => $this->currency,
            "SuccessUrl" => "https://localhost:4433/paymentmx/success?wallet=".$this->isWallet."&amount=".number_format($this->totalPrice / 100,2)."&transactionID=".$this->getTransactionID()."&userID=".$this->user->getId()."&lottery=".$this->lottery,
            "FailUrl" => "http://merchant-site.com/fail.ashx",
            "CancelUrl" => "http://merchant-site.com/cancel.ashx",
            "CheckStatusUrl" => "http://merchant-site.com/synch_check.ashx",
            "channel" => "Desktop",
            "allowPaySolChange" => "true",
            "registrationIpAddress" => $this->user->getIpAddress()->toNative(),
            "registrationDate" => ""
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