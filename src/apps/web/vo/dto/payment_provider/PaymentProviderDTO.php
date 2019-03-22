<?php


namespace EuroMillions\web\vo\dto\payment_provider;

use EuroMillions\web\interfaces\ICardPaymentProvider;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\dto\UserDTO;
use EuroMillions\web\vo\Order;
use Money\Money;

abstract class PaymentProviderDTO extends DTOBase implements IDto, \JsonSerializable
{
    private $userDto;
    private $orderVo;
    private $amountVo;
    private $cardVo;

    protected $idTransaction;
    protected $userId;
    protected $userEmail;
    protected $userIp;
    protected $userPhoneNumber;
    protected $amount;
    protected $currency;
    protected $creditCardNumber;
    protected $cvv;
    protected $expirationYear;
    protected $expirationMonth;
    protected $cardHolderName;
    protected $lotteryName;
    protected $provider;

    public function __construct(UserDTO $userDto, Order $orderVo, Money $amountVo, CreditCard $cardVo, ICardPaymentProvider $provider)
    {
        $this->userDto = $userDto;
        $this->orderVo = $orderVo;
        $this->amountVo = $amountVo;
        $this->cardVo = $cardVo;
        $this->provider = $provider;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->idTransaction = $this->orderVo->getTransactionId();
        $this->userId = $this->userDto->getUserId();
        $this->userEmail = $this->userDto->getEmail();
        $this->userIp = $this->userDto->getIp();
        $this->userPhoneNumber = $this->userDto->getPhoneNumber();
        $this->amount = $this->amountVo->getAmount();
        $this->currency = $this->amountVo->getCurrency()->getName();
        $this->creditCardNumber = $this->cardVo->getCardNumbers();
        $this->cvv = $this->cardVo->getCVV();
        $this->expirationYear = $this->cardVo->getExpiryYear();
        $this->expirationMonth = $this->cardVo->getExpiryMonth();
        $this->cardHolderName = $this->cardVo->getHolderName();
        $this->lotteryName = strtolower($this->orderVo->getLottery()->getName());
    }
}
