<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\Currency;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class CurrencyDTO extends DTOBase implements IDto
{

    protected $currency;

    public $symbol;

    public $code;

    public $name;


    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $this->symbol = $this->currency->getSymbol();
        $this->code = $this->currency->getCode()->getName();
        $this->name = $this->currency->getName();
    }

    public function toArray()
    {
    }

    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @param mixed $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}