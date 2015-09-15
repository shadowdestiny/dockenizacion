<?php


namespace EuroMillions\entities;


use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\interfaces\IEntity;

class PlayConfig extends EntityBase implements IEntity
{

    protected $id;

    protected $bet;

    protected $user;

    protected $regularNumbers;

    protected $luckyNumbers;

    protected $play_config;

    public function __construct()
    {
        $this->bet = new ArrayCollection();
    }

    public function getId()
    {

    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }




}