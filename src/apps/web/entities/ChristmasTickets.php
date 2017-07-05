<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class ChristmasTickets implements IEntity
{
    protected $id;
    protected $number;
    protected $numSeries;
    protected $serieInit;
    protected $serieEnd;
    protected $numFractions;
    protected $fractionInit;
    protected $fractionEnd;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return integer
     */
    public function getNumSeries()
    {
        return $this->numSeries;
    }

    /**
     * @param integer $numSeries
     */
    public function setNumSeries($numSeries)
    {
        $this->numSeries = $numSeries;
    }

    /**
     * @return integer
     */
    public function getSerieInit()
    {
        return $this->serieInit;
    }

    /**
     * @param integer $serieInit
     */
    public function setSerieInit($serieInit)
    {
        $this->serieInit = $serieInit;
    }

    /**
     * @return integer
     */
    public function getSerieEnd()
    {
        return $this->serieEnd;
    }

    /**
     * @param integer $serieEnd
     */
    public function setSerieEnd($serieEnd)
    {
        $this->serieEnd = $serieEnd;
    }

    /**
     * @return integer
     */
    public function getNumFractions()
    {
        return $this->numFractions;
    }

    /**
     * @param integer $numFractions
     */
    public function setNumFractions($numFractions)
    {
        $this->numFractions = $numFractions;
    }

    /**
     * @return integer
     */
    public function getFractionInit()
    {
        return $this->fractionInit;
    }

    /**
     * @param integer $fractionInit
     */
    public function setFractionInit($fractionInit)
    {
        $this->fractionInit = $fractionInit;
    }

    /**
     * @return integer
     */
    public function getFractionEnd()
    {
        return $this->fractionEnd;
    }

    /**
     * @param integer $fractionEnd
     */
    public function setFractionEnd($fractionEnd)
    {
        $this->fractionEnd = $fractionEnd;
    }

}