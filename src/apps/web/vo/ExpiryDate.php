<?php


namespace EuroMillions\web\vo;


use EuroMillions\web\exceptions\InvalidExpirationDateException;
use EuroMillions\web\vo\base\StringLiteral;

class ExpiryDate extends StringLiteral
{

    private $expiry_date;
    private $month;
    private $year;

    public function __construct($expiryDate, \DateTime $now = null)
    {
        $now = $now ?: new \DateTime();
        $date = explode('/', $expiryDate);
        if (count($date) > 1) {
            if ((int)$date[0] < 1 || (int)$date[0] > 12 || strlen($date[1]) !== 4|| strlen($date[0]) !== 2 ) {
                throw new InvalidExpirationDateException('The expiration date is not valid.');
            }
            $expires = \DateTime::createFromFormat('mY', $date[0] . $date[1]);
            if ($expires < $now) {
                throw new InvalidExpirationDateException('The expiration date is expired.');
            }
            $this->expiry_date = $expiryDate;
            $this->month = $date[0];
            $this->year = $date[1];
        } else {
            throw new InvalidExpirationDateException('The expiration date is not valid. Format should be "mm/yyyy".');
        }
        parent::__construct($expiryDate);
    }


    public function expiryDate()
    {
        return $this->expiry_date;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getYear()
    {
        return $this->year;
    }
}