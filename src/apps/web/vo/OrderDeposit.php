<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 7/06/18
 * Time: 15:39
 */

namespace EuroMillions\web\vo;


use EuroMillions\web\entities\Lottery;

use Money\Money;
use Money\Currency;

class OrderDeposit extends Order
{


    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit, Discount $discount = null, $withWallet, Lottery $lottery, $draw)
    {
        parent::__construct($play_config, $single_bet_price, $fee, $fee_limit, $discount, $withWallet, $lottery,$draw);
    }

    public function isDepositOrder()
    {
        return true;
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
}