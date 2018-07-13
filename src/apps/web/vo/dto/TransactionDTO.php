<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\AutomaticPurchaseTransaction;
use EuroMillions\web\entities\BigWinTransaction;
use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\entities\SubscriptionPurchaseTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\WinningsReceivedTransaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\entities\ManualDepositTransaction;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use Money\Currency;
use Money\Money;

class TransactionDTO extends DTOBase implements IDto
{

    protected $transaction;
    public $id;
    public $date;
    public $transactionName;
    public $movement;
    public $balance;
    public $winnings;
    public $pendingBalance;
    public $pendingBalanceMovement;
    public $ticketPrice;
    public $lotteryId;

    public function __construct(Transaction $transaction)
    {
        $transaction->fromString();
        $this->transaction = $transaction;
        $this->exChangeObject();
    }

    public function toArray()
    {

    }

    public function exChangeObject()
    {
        $this->id = $this->transaction->getId();
        $this->date = $this->transaction->getDate();
        $this->transactionName = $this->getEntityType($this->transaction);
        $this->movement = $this->transaction->getWalletAfter()->getBalance()->subtract($this->transaction->getWalletBefore()->getBalance());
        $this->balance = $this->transaction->getWalletAfter()->getBalance();
        $this->winnings = $this->transaction->getWalletAfter()->getWinnings();
        $this->pendingBalance = $this->transaction->getWalletAfter()->getSubscription();
        $this->pendingBalanceMovement = $this->transaction->getWalletAfter()->getSubscription()->subtract($this->transaction->getWalletBefore()->getSubscription());
        if ($this->transaction->getEntityType() == 'ticket_purchase') {
            $this->ticketPrice = $this->movement->add($this->pendingBalanceMovement);
        } else {
            $this->ticketPrice = new Money(0, new Currency('EUR'));
        }

    }

    public function getEntityType($transactionType)
    {
        if ($transactionType instanceof TicketPurchaseTransaction) {
            return 'Ticket Purchase';
        }
        if ($transactionType instanceof AutomaticPurchaseTransaction) {
            return 'Automatic Purchase';
        }
        if ($transactionType instanceof WinningsWithdrawTransaction) {
            return 'Winning Withdraw';
        }
        if ($transactionType instanceof DepositTransaction) {
            return 'Deposit';
        }
        if ($transactionType instanceof BigWinTransaction) {
            return 'Big Winning';
        }
        if ($transactionType instanceof WinningsReceivedTransaction) {
            return 'Winnings Received';
        }
        if ($transactionType instanceof SubscriptionPurchaseTransaction) {
            return 'Subscription Deposit';
        }
        if ($transactionType instanceof ManualDepositTransaction) {
            return 'Manual Deposit';
        }

    }

    public function formatMovement($amount)
    {
        if ($this->transaction instanceof DepositTransaction) {
            return '+' . $amount;
        }
        return $amount;
    }


}