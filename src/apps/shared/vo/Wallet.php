<?php
namespace EuroMillions\shared\vo;

use EuroMillions\shared\exceptions\NotEnoughFunds;
use EuroMillions\shared\interfaces\IArraySerializable;
use Money\Money;
use Money\Currency;
use Money\InvalidArgumentException;
use Money\UnknownCurrencyException;

class Wallet implements IArraySerializable
{
    private $uploaded;
    private $winnings;

    public function __construct(Money $uploaded = null, Money $winnings = null)
    {
        $this->uploaded = $this->initializeAmount($uploaded);
        $this->winnings = $this->initializeAmount($winnings);
    }

    /**
     * @param int|null $uploaded
     * @param int|null $winnings
     * @throws InvalidArgumentException
     * @throws UnknownCurrencyException
     * @return Wallet
     */
    public static function create($uploaded = null, $winnings = null)
    {
        return new Wallet(self::getEuros((int)$uploaded), self::getEuros((int)$winnings));
    }

    public function getBalance()
    {
        return $this->uploaded->add($this->winnings);
    }

    /**
     * @return Money
     */
    public function getWithdrawable()
    {
        return $this->winnings;
    }

    public function upload(Money $amount)
    {
        return new self($this->uploaded->add($amount), $this->winnings);
    }

    public function award(Money $amount)
    {
        return new self($this->uploaded, $this->winnings->add($amount));
    }

    public function pay(Money $amount)
    {
        if ($amount->greaterThan($this->uploaded->add($this->winnings))) {
            throw new NotEnoughFunds();
        }
        if ($amount->greaterThan($this->uploaded)) {
            $to_subtract_from_winnings = $amount->subtract($this->uploaded);
            return new self($this->initializeAmount(null), $this->winnings->subtract($to_subtract_from_winnings));
        } else {
            return new self($this->uploaded->subtract($amount), $this->winnings);
        }
    }

    public function withdraw(Money $amount)
    {
        //EMTD to be developed
    }

    /**
     * @param Money|null $amount
     * @return Money
     * @throws InvalidArgumentException
     * @throws UnknownCurrencyException
     */
    private static function initializeAmount(Money $amount = null)
    {
        if (null === $amount) {
            $amount = self::getEuros(0);
        }
        return $amount;
    }

    private static function getEuros($amount)
    {
        return new Money($amount, new Currency('EUR'));
    }

    /** @return array */
    public function toArray()
    {
        return [
            'wallet_uploaded_amount'        => $this->getUploaded()->getAmount(),
            'wallet_uploaded_currency_name' => $this->getUploaded()->getCurrency()->getName(),
            'wallet_winnings_amount'        => $this->getUploaded()->getAmount(),
            'wallet_winnings_currency_name' => $this->getUploaded()->getCurrency()->getName(),
        ];
    }

    public function equals(Wallet $wallet)
    {
        return
            $this->uploaded->getAmount() === $wallet->uploaded->getAmount() &&
            $this->winnings->getAmount() === $wallet->winnings->getAmount();
    }

    /**
     * @return Money
     * @deprecated No debería utilizarse desde fuera. Se queda para los tests.
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * @return Money
     * @deprecated No debería utilizarse desde fuera. Se queda para los tests.
     */
    public function getWinnings()
    {
        return $this->winnings;
    }

}