<?php


namespace EuroMillions\web\entities;


use EuroMillions\shared\vo\Wallet;

abstract class Transaction extends EntityBase
{

    CONST DEPOSIT_TRANSACTION_TYPE = 'deposit';
    CONST TICKET_PURCHASE_TYPE = 'ticket_purchase';
    CONST WINNING_RECEIVED_TYPE = 'winning_receive';

    protected $id;
    protected $data;
    protected $date;
    protected $wallet_before;
    protected $wallet_after;
    protected $entity_type;
    protected $user;

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * @param mixed $entity_type
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return Wallet
     */
    public function getWalletBefore()
    {
        return $this->wallet_before;
    }

    /**
     * @param mixed $wallet_before
     */
    public function setWalletBefore(Wallet $wallet_before)
    {
        $this->wallet_before = $wallet_before;
    }

    /**
     * @return Wallet
     */
    public function getWalletAfter()
    {
        return $this->wallet_after;
    }

    /**
     * @param mixed $wallet_after
     */
    public function setWalletAfter(Wallet $wallet_after)
    {
        $this->wallet_after = $wallet_after;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}