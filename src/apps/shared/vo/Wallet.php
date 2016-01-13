<?php
namespace EuroMillions\apps\shared\vo;

use Money\Money;

class Wallet
{
    private $uploaded;
    private $winnings;

    public function __construct(Money $uploaded = null, Money $winnings = null)
    {
        $this->uploaded = $uploaded;
        $this->winnings = $winnings;
    }

    public function upload(Money $amount)
    {

    }

    public function award(Money $amount)
    {

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


}