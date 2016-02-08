<?php


namespace EuroMillions\web\entities;


use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\interfaces\IEMForm;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Symfony\Component\Config\Definition\Exception\Exception;


class PlayConfig extends EntityBase implements IEntity,IEMForm
{

    protected $id;

    protected $bet;

    /** @var  User */
    protected $user;

    /** @var  EuroMillionsLine */
    protected $line;

    protected $play_config;

    protected $draw_days;

    protected $startDrawDate;

    protected $lastDrawDate;

    protected $active;

    protected $threshold;

    protected $frequency;


    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return mixed
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * @param mixed $threshold
     */
    public function setThreshold($threshold)
    {
        $this->threshold = $threshold;
    }

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


    public function formToEntity(User $user, $json, $bets)
    {
        $formPlay = null;
        try{
            $formPlay = json_decode($json,TRUE);
            if(!is_array($formPlay) || empty($formPlay)){
                throw new Exception('Error converting object to array from storage');
            }
            $this->setUser($user);
            $euroMillionsLine = [];

            foreach($bets as $bet) {
                $regular_numbers = [];
                $lucky_numbers = [];
                foreach ($bet->regular as $number) {
                    $regular_numbers[] = new EuroMillionsRegularNumber($number);
                }

                foreach ($bet->lucky as $number) {
                    $lucky_numbers[] = new EuroMillionsLuckyNumber((int) $number);
                }
                $euroMillionsLine[] = new EuroMillionsLine($regular_numbers,$lucky_numbers);
            }

            $this->setLine($euroMillionsLine);
            $this->setActive(true);
            $this->setDrawDays(new DrawDays($formPlay['drawDays']));
            $this->setStartDrawDate(new \DateTime($formPlay['startDrawDate']));
            $this->setLastDrawDate(new \DateTime($formPlay['lastDrawDate']));
            $this->setFrequency((int) $formPlay['frequency']);

        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    public function toJson()
    {
        return json_encode(get_object_vars($this));
    }
}