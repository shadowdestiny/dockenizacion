<?php


namespace EuroMillions\web\vo\dto\payment_provider;

use EuroMillions\web\interfaces\IDto;

class WireCardPaymentProviderDTO extends PaymentProviderDTO implements IDto, \JsonSerializable
{

    public function toArray()
    {
        return [
            'idTransaction' => $this->idTransaction,
            'userId' => (string) $this->userId,
            'amount' => $this->amount,
            'creditCardNumber' => $this->creditCardNumber,
            'cvc' => $this->cvv, //TODO: cvc != cvv ?
            'expirationYear' => $this->expirationYear,
            'expirationMonth' => $this->expirationMonth,
            'cardHolderName' => $this->cardHolderName,
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