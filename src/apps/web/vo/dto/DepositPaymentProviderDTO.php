<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 09/10/18
 * Time: 06:25 PM
 */

namespace EuroMillions\web\vo\dto;


class DepositPaymentProviderDTO extends OrderPaymentProviderDTO
{

    protected function createDataMoneyMatrix()
    {
        return $this->MMdata = [
            "orderID" => $this->getTransactionID(),
            "userID" => $this->user->getId(),
            "firstName" => $this->user->getName(),
            "lastName" => $this->user->getSurname(),
            "emailAddress" => $this->user->getEmail()->toNative(),
            "countryCode" => strtoupper($this->user->getDefaultLanguage()),
            "CallbackUrl" => $this->notificationEndpoint.'/notification',
            "ipAddress" => $this->user->getIpAddress()->toNative(),
            "address" => $this->user->getStreet() == null ? "" : $this->user->getStreet(),
            "city" => $this->user->getCity() == null ? "" : $this->user->getCity(),
            "phoneNumber" => $this->user->getPhoneNumber() == null ? "" : $this->user->getPhoneNumber(),
            "postalCode" => $this->user->getZip() == null ? "" : $this->user->getZip(),
            "state" => "",
            "birthDate" => "",
            "paymentMethod" => "null",
            "amount" =>  number_format($this->totalPrice / 100,2),
            "currency" => 'EUR',
            "SuccessUrl" => "https://".$this->urlEuroMillions.'/account/wallet?success=Deposit executed successfully',
            "FailUrl" => "https://".$this->urlEuroMillions.'/account/wallet?failure=Deposit fail in execution',
            "CancelUrl" => "https://".$this->urlEuroMillions.'/Euromillions/result/canel',
            "CheckStatusUrl" => "https://".$this->urlEuroMillions.'/Euromillions/result/status',
            "channel" => $this->isMobile,
            "allowPaySolChange" => "true",
            "registrationIpAddress" => $this->user->getIpAddress()->toNative(),
            "registrationDate" => ""
        ];
    }

}