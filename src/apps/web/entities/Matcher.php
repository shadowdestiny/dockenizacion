<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IUser;

class Matcher extends EntityBase implements IEntity, IUser, \JsonSerializable
{



    protected $id;
    protected $matchSide;
    protected $drawDate;
    protected $matchStatus;
    protected $matchID;
    protected $matchTypeID;
    protected $matchDate;
    protected $userId;
    protected $providerBetId;
    protected $prize;
    protected $type;
    protected $raffleMillion;
    protected $raffleRain;

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    /** @return string */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @return mixed
     */
    public function getDrawDate()
    {
        return $this->drawDate;
    }

    /**
     * @param mixed $drawDate
     */
    public function setDrawDate($drawDate)
    {
        $this->drawDate = $drawDate;
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

    /**
     * @return mixed
     */
    public function getProviderBetId()
    {
        return $this->providerBetId;
    }

    /**
     * @param mixed $providerBetId
     */
    public function setProviderBetId($providerBetId)
    {
        $this->providerBetId = $providerBetId;
    }

    /**
     * @return mixed
     */
    public function getMatchSide()
    {
        return $this->matchSide;
    }

    /**
     * @param mixed $matchSide
     */
    public function setMatchSide($matchSide)
    {
        $this->matchSide = $matchSide;
    }

    /**
     * @param mixed $matchStatus
     * @return Matcher
     */
    public function setMatchStatus($matchStatus)
    {
        $this->matchStatus = $matchStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMatchID()
    {
        return $this->matchID;
    }

    /**
     * @param mixed $matchID
     */
    public function setMatchID($matchID)
    {
        $this->matchID = $matchID;
    }

    /**
     * @return mixed
     */
    public function getMatchDate()
    {
        return $this->matchDate;
    }

    /**
     * @param mixed $matchDate
     */
    public function setMatchDate($matchDate)
    {
        $this->matchDate = $matchDate;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getMatchTypeID()
    {
        return $this->matchTypeID;
    }

    /**
     * @param mixed $matchTypeID
     */
    public function setMatchTypeID($matchTypeID)
    {
        $this->matchTypeID = $matchTypeID;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getRaffleMillion()
    {
        return $this->raffleMillion;
    }

    /**
     * @param mixed $raffleMillion
     */
    public function setRaffleMillion($raffleMillion)
    {
        $this->raffleMillion = $raffleMillion;
    }

    /**
     * @return mixed
     */
    public function getRaffleRain()
    {
        return $this->raffleRain;
    }

    /**
     * @param mixed $raffleRain
     */
    public function setRaffleRain($raffleRain)
    {
        $this->raffleRain = $raffleRain;
    }


}