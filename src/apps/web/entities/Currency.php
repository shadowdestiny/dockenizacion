<?php


namespace EuroMillions\web\entities;

use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\interfaces\IEntity;
use Money\Currency as MoneyCurrency;

class Currency extends EntityBase implements IEntity
{

    protected $code;
    protected $name;

    protected $order;

    public function getId()
    {
        return $this->getCode();
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
     * @return string
     */
    public function getSymbol()
    {
        $mf = new MoneyFormatter();
        return $mf->getSymbolFromCurrency('en_GB', new MoneyCurrency($this->getCode()));
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

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

}