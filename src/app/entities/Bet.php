<?php


namespace EuroMillions\entities;


use EuroMillions\interfaces\IEntity;

class Bet extends EntityBase implements IEntity
{
    protected $id;

    protected $play_config;

    protected $euromillionsDraw;

    protected $bet;

    public function getId()
    {
        // TODO: Implement getId() method.
    }
    public function setPlayConfig($playConfig)
    {
        $this->play_config=$playConfig;
    }

    public function getPlayConfig()
    {
        return $this->playConfig;
    }

    public function setEuroMillionsDraw($euromillionsDraw)
    {
        $this->euromillionsDraw = $euromillionsDraw;
    }

    public function getEuroMillionsDraw()
    {
        return $this->euromillionsDraw;
    }

}