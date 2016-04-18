<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\AutomaticPurchaseTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\Transaction;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class TransactionDTO extends DTOBase implements IDto
{

    protected $transaction;
    public $date;
    public $transactionName;
    public $movement;
    public $balance;
    public $winnings;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->exChangeObject();
    }

    public function toArray()
    {

    }

    public function exChangeObject()
    {
        $this->date = $this->transaction->getDate()->format('Y-m-d');
        $this->transactionName = $this->getEntityType($this->transaction);
        $this->movement = $this->transaction->getWalletBefore()->getBalance()->subtract($this->transaction->getWalletAfter()->getBalance())->getAmount();
        $this->balance = $this->transaction->getWalletAfter()->getBalance()->getAmount();
        $this->winnings = $this->transaction->getWalletAfter()->getWinnings()->getAmount();
    }

    public function getEntityType( $transactionType )
    {
        if($transactionType instanceof TicketPurchaseTransaction) {
            return 'ticket purchase';
        }
        if($transactionType instanceof AutomaticPurchaseTransaction) {
            return 'automatic purchase';
        }

    }


}