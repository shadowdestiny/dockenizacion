<?php


namespace EuroMillions\vo\dto;


use EuroMillions\entities\PaymentMethod;
use EuroMillions\interfaces\IDto;
use EuroMillions\vo\dto\base\DTOBase;

class PaymentMethodDTO extends DTOBase implements IDto
{

    /** @var  PaymentMethod */
    private $paymentMethod;

    public $id_payment;
    public $cardNumber;
    public $cardHolderName;
    public $expiry_date;
    public $cvv;
    public $company;
    public $type;
    public $last_number;

    public function __construct(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->exChangeObject();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function exChangeObject()
    {
        $this->id_payment = $this->paymentMethod->getId();
        $this->cardNumber = $this->paymentMethod->getCardNumber();
        $this->expiry_date = $this->paymentMethod->getExpiryDate()->expiry_date();
        $this->cvv = $this->paymentMethod->getCVV();
        $this->cardHolderName = $this->paymentMethod->getCardHolderName();
        $this->company = $this->paymentMethod->getCompany();
        $this->last_number = $this->getLastNumber();
    }

    private function getLastNumber()
    {
        return substr($this->paymentMethod->getCardNumber(), -4);
    }
}