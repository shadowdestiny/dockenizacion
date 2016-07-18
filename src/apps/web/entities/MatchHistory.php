<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class MatchHistory extends EntityBase implements IEntity, \JsonSerializable
{
    protected $id;
    protected $userID;
    protected $matchTypeID;
    protected $providerBetId;
    protected $drawDate;
    protected $matchStatus;
    protected $matchDate;
    protected $lPrize;
    protected $rPrize;

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
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
    public function getMatchStatus()
    {
        return $this->matchStatus;
    }

    /**
     * @param mixed $matchStatus
     */
    public function setMatchStatus($matchStatus)
    {
        $this->matchStatus = $matchStatus;
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
    public function getLPrize()
    {
        return $this->lPrize;
    }

    /**
     * @param mixed $lPrize
     */
    public function setLPrize($lPrize)
    {
        $this->lPrize = $lPrize;
    }

    /**
     * @return mixed
     */
    public function getRPrize()
    {
        return $this->rPrize;
    }

    /**
     * @param mixed $rPrize
     */
    public function setRPrize($rPrize)
    {
        $this->rPrize = $rPrize;
    }

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

    public function getId()
    {
        // TODO: Implement getId() method.
    }
}