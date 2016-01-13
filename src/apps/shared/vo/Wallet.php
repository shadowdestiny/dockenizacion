<?php
namespace EuroMillions\shared\vo;

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
     */
    public function payPreservingWinnings(Money $amount)
    {

    }

    public function payUsingWinnings(Money $amount)
    {

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