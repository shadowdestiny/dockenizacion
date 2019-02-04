<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 10:23
 */

namespace EuroMillions\shared\components\order_notifications;


use EuroMillions\shared\interfaces\IOrderNotificationBuilder;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;
use Money\Currency;
use Money\Money;

class DepositOrderNotification implements IOrderNotificationBuilder
{

    private $transaction;

    private $order;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->build();
    }

    public function giveMeOrder()
    {
        return $this->order;
    }


    protected function build()
    {
        $depositLottery = new Lottery();
        $depositLottery->initialize([
            'id'               => 1,
            'name'             => 'Deposit',
            'active'           => 1,
            'frequency'        => 'freq',
            'draw_time'        => 'draw',
            'single_bet_price' => new Money(300, new Currency('EUR')),
        ]);
        $playconfig=new PlayConfig();
        $playconfig->setFrequency(1);
        $playconfig->setUser($this->transaction->getUser());
        $money=new Money(0, new Currency('EUR'));
        $amount=new Money(intval($this->transaction->getAmountAdded()), new Currency('EUR'));
        $order=OrderFactory::create([$playconfig], $money, $money, $money, new Discount(0, []), $depositLottery , 'Deposit', $this->transaction->getWithWallet());
        $order->addFunds($amount);
        $this->order = $order;
    }
}