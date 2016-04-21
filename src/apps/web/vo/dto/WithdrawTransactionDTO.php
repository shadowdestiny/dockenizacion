<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;

class WithdrawTransactionDTO extends TransactionDTO implements IDto
{

    public $user;
    public $state;
    public $movementFormatted;

    public function exChangeObject()
    {
        parent::exChangeObject();
        $this->user = new UserDTO($this->transaction->getUser());
        $this->state = $this->transaction->getState();
        $this->movementFormatted = '€' . $this->movement->getAmount() / 100;
    }

}