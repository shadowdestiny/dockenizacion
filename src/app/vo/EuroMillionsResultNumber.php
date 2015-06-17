<?php
namespace EuroMillions\vo;


abstract class EuroMillionsResultNumber
{
    protected $number;

    abstract protected function getMinValue();
    abstract protected function getMaxValue();

    /**
     * @param int $number
     */
    public function __construct($number)
    {
        $this->setNumber($number);
    }

    public function getNumber()
    {
        return $this->number;
    }

    protected function setNumber($number)
    {
        $min_value = $this->getMinValue();
        $max_value = $this->getMaxValue();

        $error_message = "This result number should be an integer between $min_value and $max_value";
        if (!is_int($number)) {
            throw new \InvalidArgumentException($error_message);
        }
        if ($number < $min_value || $number > $max_value) {
            throw new \OutOfBoundsException($error_message);
        }
        $this->number = $number;
    }

}