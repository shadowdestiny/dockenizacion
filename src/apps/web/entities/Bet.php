<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\vo\CastilloBetId;

class Bet extends EntityBase implements IEntity
{
    protected $id;

    protected $playConfig;

    protected $euromillionsDraw;

    protected $bet;

    protected $castillo_bet;

    protected $matchNumbers;

    protected $matchStars;

    protected $prize;

    public function __construct(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw)
    {
        $this->playConfig = $playConfig;
        $this->euromillionsDraw = $euroMillionsDraw;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPlayConfig($playConfig)
    {
        $this->playConfig=$playConfig;
    }

    /**
     * @return PlayConfig
     */
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

    public function toArray()
    {
        $parent_array = parent::toArray();
        $parent_array['playConfig_id'] = $parent_array['play_config_id'];
        unset($parent_array['play_config_id']);
        if (null === $this->castillo_bet) {
            unset ($parent_array['castillo_bet']);
        }
        return $parent_array;
    }

    /**
     * @return mixed
     */
    public function getMatchNumbers()
    {
        return $this->matchNumbers;
    }

    /**
     * @param mixed $matchNumbers
     */
    public function setMatchNumbers($matchNumbers)
    {
        $this->matchNumbers = $matchNumbers;
    }

    /**
     * @return mixed
     */
    public function getMatchStars()
    {
        return $this->matchStars;
    }

    /**
     * @param mixed $matchStars
     */
    public function setMatchStars($matchStars)
    {
        $this->matchStars = $matchStars;
    }

    /**
     * @return mixed
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * @param mixed $prize
     */
    public function setPrize($prize)
    {
        $this->prize = $prize;
    }
}