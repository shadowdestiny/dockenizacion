<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\enum\OrderType;
use Money\Currency;
use Money\Money;

class Order implements \JsonSerializable
{
    /** @var  Money $total */
    protected $total;
    /** @var PlayConfig[] $play_config */
    protected $play_config;
    /** @var  Money $fee */
    protected $fee;
    /** @var  Money $fee_limit */
    protected $fee_limit;
    /** @var Money $single_bet_price */
    protected $single_bet_price;
    protected $num_lines;
    protected $state;
    protected $funds_amount;
    /** @var  CreditCardCharge $credit_card_charge */
    protected $credit_card_charge;
    /** @var bool $isCheckedWalletBalance */
    protected $isCheckedWalletBalance;
    /** @var  Money */
    protected $amountWallet;
    /** @var  Discount */
    protected $discount;

    protected $lottery;

    protected $nextDraw;

    protected $orderType;

    protected $hasSubscription;

    protected $transactionId;


    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit, Discount $discount = null,$withWallet=false, Lottery $lottery, $draw, TransactionId $transactionId=null)
    {
        $this->play_config = $play_config;
        $this->single_bet_price = $single_bet_price;
        $this->fee = $fee;
        $this->fee_limit = $fee_limit;
        $this->funds_amount = new Money(0, new Currency('EUR'));
        $this->isCheckedWalletBalance = $withWallet;
        if (!$discount) {
            $discount = new Discount(0, []);
        }
        $this->discount = $discount;
        $this->nextDraw = $draw;
        $this->lottery = $lottery;
        $this->transactionId =  $transactionId == null ? TransactionId::create() : $transactionId;
        $this->initialize();
    }


    public function getCreditCardCharge()
    {
        return $this->credit_card_charge;
    }

    public function addFunds(Money $amount = null)
    {
        if ($amount == null) {
            $amount = new Money(0, new Currency('EUR'));
        }

        $this->funds_amount = $amount;
        $total = $this->total->add($amount);
        $this->credit_card_charge = new CreditCardCharge($total, $this->fee, $this->fee_limit);
    }

    /**
     * @return Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return Money
     */
    public function getTotal()
    {
        return $this->credit_card_charge->getFinalAmount();
    }

    /**
     * @return Money
     */
    public function getTotalFromUser()
    {
        return $this->credit_card_charge->getNetAmount();
    }

    /**
     * @return Money
     */
    public function totalOriginal()
    {
        return $this->total;
    }

    /**
     * @return PlayConfig[]
     */
    public function getPlayConfig()
    {
        return $this->play_config;
    }

    /**
     * @param mixed $play_config
     */
    public function setPlayConfig($play_config)
    {
        $this->play_config = $play_config;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;

    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return mixed
     */
    public function getFeeLimit()
    {
        return $this->fee_limit;
    }

    /**
     * @param mixed $fee_limit
     */
    public function setFeeLimit($fee_limit)
    {
        $this->fee_limit = $fee_limit;
    }

    /**
     * @return mixed
     */
    public function getSingleBetPrice()
    {
        return $this->single_bet_price;
    }

    /**
     * @param mixed $single_bet_price
     */
    public function setSingleBetPrice($single_bet_price)
    {
        $this->single_bet_price = $single_bet_price;
    }

    /**
     * @return mixed
     */
    public function getNumLines()
    {
        return $this->num_lines;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @param \DateTime $draw_date
     * @return bool
     */
    public function isNextDraw(\DateTime $draw_date = null)
    {
        $play_config = $this->getPlayConfig();
        if($this->getNextDraw() != null && $draw_date == null)
        {
            return $play_config[0]->getStartDrawDate()->getTimestamp() <= $this->getNextDraw()->getDrawDate()->getTimestamp();
        }
        return $play_config[0]->getStartDrawDate()->getTimestamp() <= $draw_date->getTimestamp();
    }


    protected function initialize()
    {
        $this->num_lines = count($this->play_config);
        $this->total = new Money(1, new Currency('EUR'));
        $this->total = $this->total->multiply($this->num_lines)->multiply((int)$this->single_bet_price->getAmount())->multiply($this->play_config[0]->getFrequency());
        $this->total = $this->total->divide((($this->discount->getValue() / 100) + 1));

        if (!$this->discount->getValue()) {
            $this->total = $this->total->divide((($this->discount->getValue() / 100) + 1));
        } else {
            $this->total = new Money((int)(round($this->single_bet_price->getAmount() / (($this->discount->getValue() / 100) + 1)) * ($this->play_config[0]->getFrequency())) * $this->num_lines, new Currency('EUR'));
        }
        $this->credit_card_charge = new CreditCardCharge($this->total, $this->fee, $this->fee_limit);
    }

    public function toJsonData()
    {
        return json_encode($this);
    }

    /**
     * @return boolean
     */
    public function isIsCheckedWalletBalance()
    {
        return $this->isCheckedWalletBalance;
    }

    /**
     * @param boolean $isCheckedWalletBalance
     */
    public function setIsCheckedWalletBalance($isCheckedWalletBalance)
    {
        $this->isCheckedWalletBalance = $isCheckedWalletBalance;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {

        $bets = [];
        /** @var PlayConfig $play */
        foreach ($this->play_config as $play) {
            $bets[] = $play->jsonSerialize();
        }

        return [
            'total' => $this->total->getAmount(),
            'fee' => $this->fee->getAmount(),
            'fee_limit' => $this->fee_limit->getAmount(),
            'single_bet_price' => $this->single_bet_price->getAmount(),
            'num_lines' => $this->num_lines,
            'play_config' => $bets,
            'lottery' => $this->getLottery()->getName(),
            'transactionId' => $this->getTransactionId(),
        ];

    }

    /**
     * @param Money $amountWallet
     */
    public function setAmountWallet($amountWallet)
    {
        if ($this->isCheckedWalletBalance) {
            $this->amountWallet = $amountWallet;
            if ($this->amountWallet->greaterThan($this->total)) {
                $this->amountWallet = $this->total;
            }
            $this->total = $this->total->subtract($this->amountWallet)->add($this->funds_amount);
            $this->credit_card_charge = new CreditCardCharge($this->total, $this->fee, $this->fee_limit);
        } else {
            $this->amountWallet = new Money(0, new Currency('EUR'));
        }
    }

    /**
     * @return mixed
     */
    public function getHasSubscription()
    {
        return $this->play_config[0]->getFrequency() > 1;
    }

    /**
     * @param mixed $hasSubscription
     */
    public function setHasSubscription($hasSubscription)
    {
        $this->hasSubscription = $hasSubscription;
    }

    public function getPlayConfigsId()
    {
        return array_map(function ($val) {
            return $val->getId();
        }, $this->getPlayConfig());
    }

    public function getLottery()
    {
        return $this->lottery;
    }

    public function setLottery($lottery)
    {
        $this->lottery = $lottery;
    }

    /**
     * @return mixed
     */
    public function getNextDraw()
    {
        return $this->nextDraw;
    }

    /**
     * @param mixed $nextDraw
     */
    public function setNextDraw($nextDraw)
    {
        $this->nextDraw = $nextDraw;
    }

    public function getUnitPrice()
    {
        return $this->getPlayConfig()[0]->getSinglePrice();
    }

    public function getUnitPriceSubscription()
    {
        return $this->getPlayConfig()[0]->getSinglePrice()->multiply($this->getPlayConfig()[0]->getFrequency());
    }

    //TODO ha de ir en OrderPowerBall
    public function setData($data=null)
    {
        if($data !=null)
        {
            $powerPlayValue = (new Money($data, new Currency('EUR')))->multiply(count($this->play_config));
            $this->total =  $this->total->add($powerPlayValue);
        }
        $this->credit_card_charge = new CreditCardCharge($this->total, $this->fee, $this->fee_limit);
    }

    public function amountForTicketPurchaseTransaction()
    {
        return $this->getLottery()->getSingleBetPrice()->multiply(count($this->getPlayConfig()))->getAmount();
    }

    public function totalPlayConfigs()
    {
        return count($this->getPlayConfig());
    }

    public function isDepositOrder()
    {
        return false;
    }

    public function isWithdrawOrder()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getOrderType()
    {
        if(!isset($this->orderType))
        {
            return OrderType::DEPOSIT;
        }
        return $this->orderType;
    }

    /**
     * @param mixed $orderType
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
    }

    /**
     * @return static
     */
    public function getTransactionId()
    {
        return $this->transactionId->id();
    }
}