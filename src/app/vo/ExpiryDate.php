<?php


namespace EuroMillions\vo;


use EuroMillions\exceptions\InvalidExpirationDateException;
use EuroMillions\vo\base\StringLiteral;

class ExpiryDate extends StringLiteral
{

    private $expiry_date;

    public function __construct($expiry_date)
    {
        $this->expiry_date = $expiry_date;
        parent::__construct($expiry_date);
    }

    public static function assertExpiryDate($expiryDate)
    {
        $date = explode('/',$expiryDate);

        if(count($date) > 1){
            $expires = \DateTime::createFromFormat('my',$date[0] . $date[1]);
            $now = new \DateTime();
            if($expires < $now) throw new InvalidExpirationDateException('The expiration date is not valid');

            return true;
        }
        throw new InvalidExpirationDateException('The expiration date is not valid');
    }

    public function expiry_date()
    {
        return $this->expiry_date;
    }
}