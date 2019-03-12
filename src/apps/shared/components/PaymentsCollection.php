<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 2/08/18
 * Time: 10:54
 */

namespace EuroMillions\shared\components;


use EuroMillions\web\services\card_payment_providers\ICreditCardStrategy;

final class PaymentsCollection
{

    private $payments = [];


    public function addItem($key, ICreditCardStrategy $payment) {
        if ($key == null) {
            $this->payments[] = $payment;
        }
        else {
            if (isset($this->payments[$key])) {
                throw new \Exception("Key $key already in use.");
            }
            else {
                $this->payments[$key] = $payment;
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

    public function getAllItems()
    {
        return $this->payments;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->payments);
    }

    public function getIteratorSortByWeight(callable $function)
    {
        uasort($this->payments, $function);
        return new \ArrayIterator($this->payments);
    }

}