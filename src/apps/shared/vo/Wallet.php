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
    private $subscription;

    CONST MIN_AMOUNT_TO_WITHDRAW = 2500;

    public function __construct(Money $uploaded = null, Money $winnings = null, Money $subscription = null)
    {
        $this->uploaded = $this->initializeAmount($uploaded);
        $this->winnings = $this->initializeAmount($winnings);
        $this->subscription = $this->initializeAmount($subscription);
    }

    /**
     * @param int|null $uploaded
     * @param int|null $winnings
     * @throws InvalidArgumentException
     * @throws UnknownCurrencyException
     * @return Wallet
     */
    public static function create($uploaded = null, $winnings = null, $subscription = null)
    {
        return new Wallet(self::getEuros((int)$uploaded), self::getEuros((int)$winnings), self::getEuros((int)$subscription));
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
        return new self($this->uploaded->add($amount), $this->winnings, $this->subscription);
    }

    public function award(Money $amount)
    {
        return new self($this->uploaded, $this->winnings->add($amount), $this->subscription);
    }

    public function pay(Money $amount)
    {
        if ($amount->greaterThan($this->uploaded->add($this->winnings))) {
            throw new NotEnoughFunds();
        }
        if ($amount->greaterThan($this->uploaded)) {
            $to_subtract_from_winnings = $amount->subtract($this->uploaded);
            return new self($this->initializeAmount(null), $this->winnings->subtract($to_subtract_from_winnings), $this->subscription);
        } else {
            return new self($this->uploaded->subtract($amount), $this->winnings, $this->subscription);
        }
    }

    //TODO: @etey o @benair3 crear TEST!!!!!
    public function payWithSubscription(Money $amount)
    {
        return new self($this->uploaded, $this->winnings, $this->subscription->subtract($amount));
    }

    public function paySubscriptionWithWallet(Money $amount)
    {
        if ($amount->greaterThan($this->uploaded->add($this->winnings))) {
            throw new NotEnoughFunds();
        }
        if ($amount->greaterThan($this->uploaded)) {
            $to_subtract_from_winnings = $amount->subtract($this->uploaded);
            return new self($this->uploaded->subtract($amount->subtract($to_subtract_from_winnings)), $this->winnings->subtract($to_subtract_from_winnings), $this->subscription->add($amount));
        } else {
            return new self($this->uploaded->subtract($amount), $this->winnings, $this->subscription->add($amount));
        }
    }

    public function removeWalletToSubscription(Money $amount)
    {
        if ($amount->greaterThan($this->uploaded->add($this->winnings))) {
            $to_add = $this->uploaded->add($this->winnings);

            return new self($this->uploaded->subtract($this->uploaded), $this->winnings->subtract($this->winnings), $this->subscription->add($to_add));
        } else {
            if ($amount->greaterThan($this->uploaded)) {
                $to_subtract_from_winnings = $amount->subtract($this->uploaded);

                return new self($this->uploaded->subtract($this->uploaded), $this->winnings->subtract($to_subtract_from_winnings), $this->subscription->add($amount));
            } else {
                return new self($this->uploaded->subtract($amount), $this->winnings, $this->subscription->add($amount));
            }
        }

    }

    public function uploadToSubscription(Money $amount)
    {
        return new self($this->uploaded, $this->winnings, $this->subscription->add($amount));
    }

    public function withdraw(Money $amount)
    {
        $minAmountToWithdraw = new Money(self::MIN_AMOUNT_TO_WITHDRAW, new Currency('EUR'));
        if ($amount->lessThan($minAmountToWithdraw)) {
            throw new NotEnoughFunds();
        }
        if ($amount->lessThan($this->winnings)) {
            $substract_from_winnings = $this->winnings->subtract($amount);
            return new self($this->uploaded, $substract_from_winnings, $this->subscription);
        }
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
            'uploaded_amount' => $this->getUploaded()->getAmount(),
            'uploaded_currency_name' => $this->getUploaded()->getCurrency()->getName(),
            'winnings_amount' => $this->getUploaded()->getAmount(),
            'winnings_currency_name' => $this->getUploaded()->getCurrency()->getName(),
            'subscription_amount' => $this->getUploaded()->getAmount(),
            'subscription_currency_name' => $this->getUploaded()->getCurrency()->getName()
        ];
    }

    public function equals(Wallet $wallet)
    {
        return
            $this->uploaded->getAmount() === $wallet->uploaded->getAmount() &&
            $this->winnings->getAmount() === $wallet->winnings->getAmount() &&
            $this->subscription->getAmount() === $wallet->subscription->getAmount();
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

    /**
     * @return Money
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

}