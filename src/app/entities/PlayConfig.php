<?php


namespace EuroMillions\entities;


use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\interfaces\IEntity;
use EuroMillions\interfaces\IEMForm;
use EuroMillions\vo\EuroMillionsLine;
use Symfony\Component\Config\Definition\Exception\Exception;


class PlayConfig extends EntityBase implements IEntity,IEMForm
{

    protected $id;

    protected $bet;

    protected $user;

    /** @var  EuroMillionsLine */
    protected $line;

    protected $play_config;

    protected $draw_days;

    protected $startDrawDate;

    protected $lastDrawDate;

    protected $active;

    public function __construct()
    {
        $this->bet = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    public function getId()
    {
        return $this->id;
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

    public function setDrawDays($draw_days)
    {
        $this->draw_days = $draw_days;
    }

    public function getDrawDays()
    {
        return $this->draw_days;
    }

    public function setStartDrawDate($startDrawDate)
    {
        $this->startDrawDate = $startDrawDate;
    }

    public function getStartDrawDate()
    {
        return $this->startDrawDate;
    }

    public function setLastDrawDate($lastDrawDate)
    {
        $this->lastDrawDate = $lastDrawDate;
    }

    public function getLastDrawDate()
    {
        return $this->lastDrawDate;
    }

    public function setBet($bet)
    {
        $this->bet = $bet;
    }

    public function getBet()
    {
        return $this->bet;
    }


    public function formToEntity(User $user, $json)
    {
        $formPlay = null;
        try{

            $formPlay = json_decode($json);
            if(!is_array($formPlay)){
                throw new Exception('Error converting object to array from storage');
            }

            $this->setUser($user);
            $this->setLine($formPlay['euroMillionsLines']);
            $this->setActive(true);
            $this->setDrawDays(new \DrawDays($formPlay['drawDays']));
            $this->setStartDrawDate($formPlay['startDrawDate']);
            $this->setLastDrawDate($formPlay['lastDrawDate']);
        }catch(Exception $e){

        }


    }
}