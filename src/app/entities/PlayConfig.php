<?php


namespace EuroMillions\entities;


use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\interfaces\IEntity;
use EuroMillions\vo\EuroMillionsLine;

class PlayConfig extends EntityBase implements IEntity
{

    protected $id;

    protected $bet;

    protected $user;

    /** @var  EuroMillionsLine */
    protected $line;

    protected $play_config;

    protected $playConfigDateEnd;

    protected $active;

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

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function getActive()
    {
        return $this->active;
    }

}