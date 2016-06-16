<?php


namespace EuroMillions\web\entities;


use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IUser;

class Matcher extends EntityBase implements IEntity, IUser, \JsonSerializable
{



    protected $id;
    protected $drawDate;
    protected $idTicket;
    protected $prize;

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
    public function getIdTicket()
    {
        return $this->idTicket;
    }

    /**
     * @param mixed $idTicket
     */
    public function setIdTicket($idTicket)
    {
        $this->idTicket = $idTicket;
    }


}