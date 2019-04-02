<?php

namespace EuroMillions\web\vo\dto\order_payment_provider\withdraw;

use EuroMillions\web\vo\dto\order_payment_provider\OrderPaymentProviderDTO;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\enum\MoneyMatrixEndpoint;

class MoneyMatrixWithdrawOrderPaymentProviderDTO extends OrderPaymentProviderDTO implements IDto, \JsonSerializable
{
    public function exChangeObject()
    {
        parent::exChangeObject();
        $this->notificationEndpoint = $this->config['moneymatrix']->endpoint;
    }

    public function toArray()
    {
        return [
            "orderID" => $this->getTransactionID(),
            "userID" => $this->userDto->getUserId(),
            "firstName" => $this->userDto->getName(),
            "lastName" => $this->userDto->getSurname(),
            "emailAddress" => $this->userDto->getEmail(),
            "countryCode" => strtoupper($this->userDto->getDefaultLanguage()) == 'EN' ? 'GB' : strtoupper($this->userDto->getDefaultLanguage()),
            "CallbackUrl" => $this->notificationEndpoint.'/notification',
            "ipAddress" => $this->userDto->getIp(),
            "address" => $this->userDto->getStreet() == null ? "" : $this->userDto->getStreet(),
            "city" => $this->userDto->getCity() == null ? "" : $this->userDto->getCity(),
            "phoneNumber" => $this->userDto->getPhoneNumber() == null ? "" : $this->userDto->getPhoneNumber(),
            "postalCode" => $this->userDto->getZip() == null ? "" : $this->userDto->getZip(),
            "state" => "",
            "birthDate" => "",
            "paymentMethod" => "null",
            "amount" => number_format($this->totalPrice / 100, 2, '.', ''),
            "currency" => 'EUR',
            "SuccessUrl" => "https://".$this->urlEuroMillions.'/account/wallet?success=WithDraw executed successfully',
            "FailUrl" => "https://".$this->urlEuroMillions.'/account/wallet?failure=WithDraw fail in execution',
            "CancelUrl" => "https://".$this->urlEuroMillions.'/Euromillions/result/canel',
            "CheckStatusUrl" => $this->config['moneymatrix']->endpoint.'/withdraw/status',
            "channel" => $this->isMobile,
            "allowPaySolChange" => "true",
            "registrationIpAddress" => $this->userDto->getIp(),
            "registrationDate" => ""
        ];


    }

    public function action()
    {
        return MoneyMatrixEndpoint::WITHDRAW;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize ()
    {
        return $this->toArray();
    }

}
