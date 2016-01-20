<?php


namespace tests\helpers\mothers;


use EuroMillions\web\vo\CardHolderName;
use EuroMillions\web\vo\CardNumber;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\CVV;
use EuroMillions\web\vo\ExpiryDate;

class CreditCardMother
{
    /**
     * @returns CreditCard
     */
    public static function aValidCreditCard()
    {
        return new CreditCard(
            new CardHolderName('Pagafantas'),
            new CardNumber('4012888888881881'),
            new ExpiryDate('2024/10'),
            new CVV('233')
        );
    }

}