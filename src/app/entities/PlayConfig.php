<?php


namespace EuroMillions\entities;


use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\interfaces\IEntity;

class PlayConfig extends EntityBase implements IEntity
{

    protected $id;

    protected $bet;

    protected $user;

    /** @var  EuroMillionsResult */
    protected $line;

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

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function getLine()
    {
        return $this->line;
    }




}