<?php


namespace EuroMillions\web\vo;

use Assert\Assertion;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use EuroMillions\web\vo\base\StringLiteral;
use Symfony\Component\Config\Definition\Exception\Exception;

class Raffle
{
    protected $value;

    public function __construct($value)
    {
        Assertion::notEmpty($value);
        $this->value = $value;
     //   $this->tipoIsCorrect($value);
    }

    public function tipoIsCorrect($raffle)
    {
        $regex = '/^[A-Z]{3}[0-9]{5}$/';
        if (preg_match($regex, $raffle)) {
            $this->value = $raffle;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    public function equals(Raffle $raffle)
    {
        if ($raffle->getValue() == $this->value) {
            return true;
        }
        return false;
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

    public function toArray()
    {
        return [
            'value' => $this->value
        ];
    }

}