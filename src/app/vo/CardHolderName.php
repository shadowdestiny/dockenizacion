<?php


namespace EuroMillions\vo;


use EuroMillions\vo\base\StringLiteral;

class CardHolderName extends StringLiteral
{
    const MIN_LENGTH = 5;
    const MAX_LENGTH = 50;
    const FORMAT = '/^[a-zA-Z_]+$/';

    private $cardHolderName;

    public function __construct($cardHolderName)
    {
        $this->setCardHolderName($cardHolderName);
    }

    private function setCardHolderName($cardHolderName)
    {
       // $this->assertNotEmpty($cardHolderName);
       // $this->assertNotTooShort($cardHolderName);
       // $this->assertNotTooLong($cardHolderName);
       // $this->assertValidFormat($cardHolderName);
        $this->$cardHolderName = $cardHolderName;
    }

    public function cardHolderName()
    {
        return $this->cardHolderName;
    }
}