<?php


namespace EuroMillions\web\services\card_payment_providers\payments_util;


class PaymentsCollection
{

    private $payments = [];

    public function __construct(array $payments)
    {
        $this->payments = $payments;
    }

    public function addItem($obj, $key = null) {
        if ($key == null) {
            $this->payments[] = $obj;
        }
        else {
            if (isset($this->payments[$key])) {
                throw new \Exception("Key $key already in use.");
            }
            else {
                $this->payments[$key] = $obj;
            }
        }
    }

    public function deleteItem($key) {
        if (isset($this->payments[$key])) {
            unset($this->payments[$key]);
    }
        else {
            throw new \Exception("Invalid key $key.");
        }
    }

    public function getItem($key) {
        if (isset($this->payments[$key])) {
            return $this->payments[$key];
        }
        else {
            throw new \Exception("Invalid key $key.");
        }
    }

    public function getAll() {
        return $this->payments;
    }

    public function keys() {
        return array_keys($this->payments);
    }
}
