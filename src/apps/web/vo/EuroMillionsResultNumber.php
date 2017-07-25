<?php
namespace EuroMillions\web\vo;


abstract class EuroMillionsResultNumber
{
    protected $number;

    abstract protected function getMinValue();
    abstract protected function getMaxValue();

    /**
     * @param int $number
     */
    public function __construct($number, $lottery = null)
    {
            $this->setNumber($number, $lottery);
    }

    public function getNumber()
    {
        return $this->number;
    }

    protected function setNumber($number, $lottery)
    {
        $min_value = $this->getMinValue();
        $max_value = $this->getMaxValue();
        if ($lottery) {
            $min_value = 0;
            $max_value = 200;
        }

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