<?php
/**
 * Created by PhpStorm.
 * User: vapdl
 * Date: 25/10/18
 * Time: 07:31 AM
 */

namespace EuroMillions\web\vo;

use EuroMillions\web\entities\Lottery;

use EuroMillions\web\vo\enum\OrderType;
use Money\Money;
use Money\Currency;

class WithdrawOrder extends Order
{
    public function __construct(array $play_config, Money $single_bet_price, Money $fee, Money $fee_limit, Discount $discount = null, $withWallet, Lottery $lottery, $draw)
    {
        parent::__construct($play_config, $single_bet_price, $fee, $fee_limit, $discount, $withWallet, $lottery,$draw);
    }

    public function isWithdrawOrder()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getOrderType()
    {
        return OrderType::WINNINGS_WITHDRAW;
    }

}