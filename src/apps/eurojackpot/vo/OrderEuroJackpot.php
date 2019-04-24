<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 6/11/18
 * Time: 15:18
 */

namespace EuroMillions\eurojackpot\vo;


use EuroMillions\web\entities\Lottery;
use EuroMillions\web\vo\CreditCardCharge;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\enum\OrderType;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\TransactionId;
use Money\Currency;
use Money\Money;

final class OrderEuroJackpot extends Order
{

    private $powerPlay;


    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit, Discount $discount = null, $withWallet, Lottery $lottery, $draw,TransactionId $transactionId=null)
    {
        parent::__construct($play_config, $single_bet_price, $fee, $fee_limit, $discount, $withWallet, $lottery,$draw,$transactionId);
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

    public function addFunds(Money $amount = null)
    {
        if ($amount == null) {
            $amount = new Money(0, new Currency('EUR'));
        }
        $frequency = $this->play_config[0]->getFrequency();
        $multiplier = $frequency;
        $price = new Money(0, new Currency('EUR'));
        $total = $this->total->add($price);
        $this->total = $total;
        $this->credit_card_charge = new CreditCardCharge($total, $this->fee, $this->fee_limit);

    }

    public function getAmount()
    {
        $numPlayConfigs = count($this->getPlayConfig());

        $this->lottery->getSingleBetPrice()->multiply($numPlayConfigs)->getAmount();
    }

    public function setLottery($lottery)
    {
        /** @var Lottery $lottery */
        $this->lottery = $lottery;
    }

    public function getCreditCardCharge()
    {
        return $this->credit_card_charge;
    }

    public function getUnitPrice()
    {
        return $this->getPlayConfig()[0]->getSinglePrice();
    }

    public function getUnitPriceSubscription()
    {
        return $this->getPlayConfig()[0]->getSinglePrice()->multiply($this->getPlayConfig()[0]->getFrequency());
    }

    public function amountForTicketPurchaseTransaction()
    {
        return $this->getLottery()->getSingleBetPrice()->multiply(count($this->getPlayConfig()))->getAmount();
    }


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
     * @return null
     */
    public function getPowerPlay()
    {
        return $this->powerPlay;
    }

    /**
     * @param null $powerPlay
     */
    public function setPowerPlay($powerPlay)
    {
        $this->powerPlay = $powerPlay;
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


}