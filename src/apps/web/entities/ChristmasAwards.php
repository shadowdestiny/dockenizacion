<?php

namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;

class ChristmasAwards implements IEntity
{
    protected $id;
    protected $number;
    protected $christmasTicketId;
    protected $prize;

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
     * @return mixed
     */
    public function getChristmasTicketId()
    {
        return $this->christmasTicketId;
    }

    /**
     * @param mixed $christmasTicketId
     */
    public function setChristmasTicketId($christmasTicketId)
    {
        $this->christmasTicketId = $christmasTicketId;
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