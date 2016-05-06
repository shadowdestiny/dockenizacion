<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\AutomaticPurchaseTransaction;
use EuroMillions\web\entities\DepositTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class TransactionDTO extends DTOBase implements IDto
{

    protected $transaction;
    public $id;
    public $date;
    public $transactionName;
    public $movement;
    public $balance;
    public $winnings;

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
        $this->date = $this->transaction->getDate()->format('Y-m-d H:i:s');
        $this->transactionName = $this->getEntityType($this->transaction);
        $this->movement = $this->transaction->getWalletAfter()->getBalance()->subtract($this->transaction->getWalletBefore()->getBalance());
        $this->balance = $this->transaction->getWalletAfter()->getBalance();
        $this->winnings = $this->transaction->getWalletAfter()->getWinnings();
    }

    public function getEntityType( $transactionType )
    {
        if($transactionType instanceof TicketPurchaseTransaction) {
            return 'Ticket Purchase';
        }
        if($transactionType instanceof AutomaticPurchaseTransaction) {
            return 'Automatic Purchase';
        }
        if($transactionType instanceof WinningsWithdrawTransaction ) {
            return 'Winning Withdraw';
        }
        if($transactionType instanceof DepositTransaction ) {
            return 'Deposit';
        }

    }

    public function formatMovement( $amount )
    {
        if( $this->transaction instanceof DepositTransaction )
        {
            return '+' . $amount;
        }
        return $amount;
    }


}