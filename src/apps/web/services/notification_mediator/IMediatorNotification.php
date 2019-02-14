<?php


namespace EuroMillions\web\services\notification_mediator;


use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Order;

interface IMediatorNotification
{

    public function playConfigValidate();

    public function persistBet();

    public function log();

    public function purchaseTransaction();

    public function updateTransaction(User $user, Order $order, $transactionID, $walletBefore);

    public function sendEmail();


}