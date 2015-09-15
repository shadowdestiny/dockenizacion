<?php


namespace EuroMillions\entities;


use EuroMillions\interfaces\IEntity;

class Bet extends EntityBase implements IEntity
{
    protected $id;

    protected $regularNumbers;

    protected $luckyNumbers;

    protected $playConfig;

    protected $bet;

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function setRegularNumbers($regularNumbers)
    {

    }

    public function getRegularNumbers()
    {
        return $this->regularNumbers;
    }

    public function setLuckyNumbers($luckyNumbers)
    {
        $this->luckyNumbers=$luckyNumbers;
    }

    public function getLuckyNumbers()
    {
        return $this->luckyNumbers;
    }

    public function setPlayConfig($playConfig)
    {
        $this->playConfig=$playConfig;
    }

    public function getPlayConfig()
    {
        return $this->playConfig;
    }

}