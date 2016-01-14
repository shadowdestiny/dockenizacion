<?php
namespace EuroMillions\shared\vo;

use EuroMillions\shared\exceptions\NotEnoughFunds;
use Money\Currency;
use Money\InvalidArgumentException;
use Money\Money;
use Money\UnknownCurrencyException;

class Wallet
{
    private $uploaded;
    private $winnings;

    public function __construct(Money $uploaded = null, Money $winnings = null)
    {
        $this->uploaded = $this->initializeAmount($uploaded);
        $this->winnings = $this->initializeAmount($winnings);
    }

    public function upload(Money $amount)
    {
        $this->uploaded = $this->uploaded->add($amount);
    }

    public function award(Money $amount)
    {
        $this->winnings = $this->winnings->add($amount);
    }

    /**
     * @param Money $amount
     * @throws NotEnoughFunds
     */
    public function payPreservingWinnings(Money $amount)
    {
        if($this->uploaded->lessThan($amount)) {
            throw new NotEnoughFunds();
        }
        $this->uploaded = $this->uploaded->subtract($amount);
    }

    public function payUsingWinnings(Money $amount)
    {
        if ($amount->greaterThan($this->uploaded->add($this->winnings))) {
            throw new NotEnoughFunds();
        }
        if ($amount->greaterThan($this->uploaded)) {
            $to_subtract_from_winnings = $amount->subtract($this->uploaded);
            $this->uploaded = $this->initializeAmount(null);
            $this->winnings = $this->winnings->subtract($to_subtract_from_winnings);
        } else {
            $this->uploaded = $this->uploaded->subtract($amount);
        }
    }

    public function withdraw(Money $amount)
    {

    }

    /**
     * @return Money
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * @return Money
     */
    public function getWinnings()
    {
        return $this->winnings;
    }

    /**
     * @param Money $amount
     * @return Money
     * @throws InvalidArgumentException
     * @throws UnknownCurrencyException
     */
    private function initializeAmount(Money $amount = null)
    {
        if (null === $amount) {
            $amount = new Money(0, new Currency('EUR'));
        }
        return $amount;
    }

}