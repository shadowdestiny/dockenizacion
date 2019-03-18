<?php


namespace EuroMillions\web\vo\dto\payment_provider;

use EuroMillions\web\interfaces\IDto;

class RoyalPayPaymentProviderDTO extends PaymentProviderDTO implements IDto, \JsonSerializable
{

    public function toArray()
    {
        return [
            'orderID' => $this->idTransaction,
            'userID' => (string) $this->userId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            "CallbackUrl" => $this->provider->getConfig()->getEndpointCallbacks(),
	        "SuccessUrl" => "https://".$this->provider->getConfig()->getMerchantDomain().$this->lotteryName."/result/success",
            "FailUrl" => "https://".$this->provider->getConfig()->getMerchantDomain().$this->lotteryName."/result/failure",
            "PendingUrl" => "https://".$this->provider->getConfig()->getMerchantDomain().$this->lotteryName."/result/success",
            'cardNumber' => $this->creditCardNumber,
            'cardCvv' => $this->cvv,
            'cardYear' => $this->expirationYear,
            'cardMonth' => $this->expirationMonth,
            'cardHolderName' => $this->cardHolderName
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}