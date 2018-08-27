<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 25/08/18
 * Time: 17:16
 */

namespace EuroMillions\web\services\factories;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\Order;
use EuroMillions\web\vo\OrderPowerBall;
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
                                  $withWallet
    )
    {
        /** @var User $user */
        $user = $play_config[0]->getUser();
        if($lottery->getName() == 'PowerBall')
        {
            $order = new OrderPowerBall($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet);
            $order->setLottery($lottery);
            $order->setNextDraw($draw);
            $order->setAmountWallet($user->getWallet()->getBalance());
            return $order;
        }
        $order = new Order($play_config, $single_bet_price, $fee_value, $fee_to_limit_value, $discount,$withWallet);
        $order->setNextDraw($draw);
        $order->setLottery($lottery);
        $order->setAmountWallet($user->getWallet()->getBalance());
        return $order;
    }


}