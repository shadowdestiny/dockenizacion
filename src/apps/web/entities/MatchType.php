<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;

class MatchType extends EntityBase implements IEntity, \JsonSerializable
{


    protected $id;
    protected $matchName;
    protected $transactionType;
    protected $leftEdge;
    protected $rightEdge;
    protected $lottery;


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

    /**
     * @return mixed
     */
    public function getMatchName()
    {
        return $this->matchName;
    }

    /**
     * @param mixed $matchName
     */
    public function setMatchName($matchName)
    {
        $this->matchName = $matchName;
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return mixed
     */
    public function getLeftEdge()
    {
        return $this->leftEdge;
    }

    /**
     * @param mixed $leftEdge
     */
    public function setLeftEdge($leftEdge)
    {
        $this->leftEdge = $leftEdge;
    }

    /**
     * @return mixed
     */
    public function getRightEdge()
    {
        return $this->rightEdge;
    }

    /**
     * @param mixed $rightEdge
     */
    public function setRightEdge($rightEdge)
    {
        $this->rightEdge = $rightEdge;
    }

    /**
     * @return mixed
     */
    public function getLottery()
    {
        return $this->lottery;
    }

    /**
     * @param mixed $lottery
     */
    public function setLottery($lottery)
    {
        $this->lottery = $lottery;
    }

}