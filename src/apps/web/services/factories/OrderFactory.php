<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/08/18
 * Time: 17:16
 */

namespace EuroMillions\web\services\factories;


use EuroMillions\eurojackpot\vo\OrderEuroJackpot;
use EuroMillions\megasena\vo\OrderMegaSena;
use EuroMillions\megamillions\vo\OrderMegaMillions;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\superenalotto\vo\OrderSuperEnalotto;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\OrderChristmas;
use EuroMillions\web\vo\OrderPowerBall;
use EuroMillions\web\vo\OrderDeposit;
use EuroMillions\web\vo\TransactionId;
use EuroMillions\web\vo\WithdrawOrder;
use Money\Money;

class OrderFactory
{


    public static function create(array $play_config,
                                  Money $single_bet_price,
                                  Money $fee_value,
                                  Money $fee_to_limit_value,
                                  Discount $discount,
                                  Lottery $lottery,
                                  $draw,
                                  $withWallet,
                                  TransactionId $transactionId = null
    )
    {
        /** @var User $user */
        $user = $play_config[0]->getUser();
        if($lottery->getName() == 'PowerBall')
        {
            $order = new OrderPowerBall($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw,$transactionId);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif($lottery->getName() == 'Christmas')
        {
            $order = new OrderChristmas($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif($lottery->getName() == 'MegaMillions')
        {
            $order = new OrderMegaMillions($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw,$transactionId);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif ($lottery->getName() == 'EuroJackpot')
        {
            $order = new OrderEuroJackpot($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw,$transactionId);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif ($lottery->getName() == 'MegaSena')
        {
            $order = new OrderMegaSena($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif ($lottery->getName() == 'SuperEnalotto')
        {
            $order = new OrderSuperEnalotto($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif($draw == 'Deposit')
        {
            $order = new OrderDeposit($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw, $transactionId);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        elseif($draw == 'Withdraw')
        {
            $order = new WithdrawOrder($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw, $transactionId);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        $order = new Order($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet,$lottery,$draw,$transactionId);
        $order->setAmountWallet($user->getWallet()->getBalance());
        return $order;
    }


}