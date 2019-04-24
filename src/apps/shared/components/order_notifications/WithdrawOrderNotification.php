<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/10/18
 * Time: 17:14
 */

namespace EuroMillions\shared\components\order_notifications;


use EuroMillions\shared\interfaces\IOrderNotificationBuilder;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\TransactionId;
use Money\Currency;
use Money\Money;

class WithdrawOrderNotification implements IOrderNotificationBuilder
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
        $withdrawLottery = new Lottery();
        $withdrawLottery->initialize([
            'id'               => 1,
            'name'             => 'Withdraw',
            'active'           => 1,
            'frequency'        => 'freq',
            'draw_time'        => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $this->transaction->fromString();
        $playconfig=new PlayConfig();
        $playconfig->setFrequency(1);
        $playconfig->setUser($this->transaction->getUser());
        $money=new Money(0, new Currency('EUR'));
        $amount=new Money(intval($this->transaction->getAmountWithdrawed()), new Currency('EUR'));
        $order=OrderFactory::create([$playconfig], $money, $money, $money, new Discount(0, []), $withdrawLottery , 'Withdraw', 0, new TransactionId($this->transaction->getTransactionID()));
        $order->addFunds($amount);
        $this->order = $order;
    }}