<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\PaymentMethod;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\Order;
use Money\Money;

class PaymentProviderDTO extends DTOBase implements IDto, \JsonSerializable
{

    private $userDto;
    private $orderVo;
    private $amountVo;
    private $cardVo;

    protected $idTransaction;
    protected $userId;
    protected $amount;
    protected $creditCarNumber;
    protected $cvv;
    protected $expirationYear;
    protected $expirationMonth;
    protected $cardHolderName;

    public function __construct(UserDTO $userDto, Order $orderVo, Money $amountVo, CreditCard $cardVo)
    {
        $this->userDto = $userDto;
        $this->orderVo = $orderVo;
        $this->amountVo = $amountVo;
        $this->cardVo = $cardVo;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->idTransaction = $this->orderVo->getTransactionId();
        $this->userId = $this->userDto->getUserId();
        $this->amount = $this->amountVo->getAmount();
        $this->creditCarNumber = $this->cardVo->getCardNumbers();
        $this->cvv = $this->cardVo->getCVV();
        $this->expirationYear = $this->cardVo->getExpiryYear();
        $this->expirationMonth = $this->cardVo->getExpiryMonth();
        $this->cardHolderName = $this->cardVo->getHolderName();
    }

    public function toArray()
    {
        return [
            'idTransaction' => $this->idTransaction,
            'userId' => (string) $this->userDto->getUserId(),
            'amount' => $this->amount,
            'creditCarNumber' => $this->creditCarNumber,
            'cvv' => $this->cvv,
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
    public function jsonSerialize ()
    {
        return $this->toArray();
    }
}