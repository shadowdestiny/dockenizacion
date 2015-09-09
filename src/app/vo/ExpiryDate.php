<?php


namespace EuroMillions\vo;


use EuroMillions\exceptions\InvalidExpirationDateException;

class ExpiryDate
{

    private $expiryDate;

    public function __construct()
    {

    }

    public function assertExpiryDate($expiryDate)
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
}