<?php


namespace EuroMillions\web\vo;

use Assert\Assertion;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use EuroMillions\web\vo\base\StringLiteral;
use Symfony\Component\Config\Definition\Exception\Exception;

class Raffle extends StringLiteral
{
    protected $value;

    public function __construct($value)
    {
        Assertion::notEmpty($value);
        $this->tipoIsCorrect($value);
        parent::__construct($value);
    }

    public function tipoIsCorrect($raffle)
    {
        $regex = '/^[A-Z]{3}[0-9]{5}$/';
        if (preg_match($regex, $raffle)) {
            return true;
        }
        throw new \InvalidArgumentException();
    }

    public function equals(Raffle $raffle)
    {

        return true;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getRaffleChars()
    {
        return substr($this->value, 0, 2);
    }

    public function getRaffleNumbers()
    {
        return substr($this->value, 3, 7);
    }

}