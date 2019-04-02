<?php

namespace EuroMillions\web\vo\dto\order_payment_provider\withdraw;

use EuroMillions\web\vo\dto\order_payment_provider\OrderPaymentProviderDTO;
use EuroMillions\web\interfaces\IDto;

class WithdrawOrderPaymentProviderDTO extends OrderPaymentProviderDTO implements IDto, \JsonSerializable
{
    public function exChangeObject()
    {
        parent::exChangeObject();
    }

    public function toArray()
    {
        throw new \Exception('Not Implemented');
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     *
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
