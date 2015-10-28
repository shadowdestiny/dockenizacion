<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\vo\CastilloBetId;

class Bet extends EntityBase implements IEntity
{
    protected $id;

    protected $play_config;

    protected $euromillionsDraw;

    protected $bet;

    protected $castillo_bet;

    public function __construct(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw)
    {
        $this->play_config = $playConfig;
        $this->euromillionsDraw = $euroMillionsDraw;
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }
    public function setPlayConfig($playConfig)
    {
        $this->play_config=$playConfig;
    }

    /**
     * @return PlayConfig
     */
    public function getPlayConfig()
    {
        return $this->play_config;
    }

    public function setEuroMillionsDraw($euromillionsDraw)
    {
        $this->euromillionsDraw = $euromillionsDraw;
    }

    public function getEuroMillionsDraw()
    {
        return $this->euromillionsDraw;
    }


    /**
     * @return CastilloBetId
     */
    public function getCastilloBet()
    {
        return $this->castillo_bet;
    }

    /**
     * @param mixed $castillo_bet
     */
    public function setCastilloBet($castillo_bet)
    {
        $this->castillo_bet = $castillo_bet;
    }

}