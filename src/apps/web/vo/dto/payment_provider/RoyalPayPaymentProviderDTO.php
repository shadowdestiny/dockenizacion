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
            'userPhoneNumber' => (string) $this->userPhoneNumber,
            'amount' => $this->amount,
            'currency' => $this->currency,
            "CallbackUrl" => $this->provider->getConfig()->getEndpointCallbacks(),
	        "SuccessUrl" => $this->getSucessUrl(),
            "FailUrl" => $this->getFailUrl(),
            "PendingUrl" => $this->getPendingUrl(),
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

    private function getSucessUrl()
    {
        $url = "https://".$this->provider->getConfig()->getMerchantDomain().$this->lotteryName."/result/success";

        if($this->lotteryName == "deposit") {
            $url = "https://".$this->provider->getConfig()->getMerchantDomain()."account/wallet";
        }

        return $url;
    }

    private function getFailUrl()
    {
        $url = "https://".$this->provider->getConfig()->getMerchantDomain()."euromillions/result/failure";

        if($this->lotteryName == "deposit") {
            $url = "https://".$this->provider->getConfig()->getMerchantDomain()."account/wallet";
        }

        return $url;
    }

    private function getPendingUrl()
    {
        return $this->getSucessUrl();
    }
}