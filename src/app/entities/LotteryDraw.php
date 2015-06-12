<?php
namespace EuroMillions\entities;

use Doctrine\Common\Collections\ArrayCollection;
use EuroMillions\exceptions\MissingRelationException;
use EuroMillions\interfaces\IEntity;

class LotteryDraw extends EntityBase implements IEntity
{
    protected $draw_id;
    protected $draw_date;
    protected $jackpot;
    protected $message;
    protected $big_winner;

    protected $published;
    /** @var  Lottery */
    protected $lottery;
    /** @var  LotteryResult */
    protected $result;

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getLottery()
    {
        return $this->lottery;
    }

    public function setLottery($lottery)
    {
        $this->lottery = $lottery;
    }

    public function setDrawId($draw_id)
    {
        $this->draw_id = $draw_id;
    }

    public function getId()
    {
        return $this->draw_id;

    }

    public function getDrawDate()
    {
        return $this->draw_date;
    }

    public function setDrawDate($draw_date)
    {
        $this->draw_date = $draw_date;
    }

    public function getJackpot()
    {
        return $this->jackpot;
    }

    public function setJackpot($jackpot)
    {
        $this->jackpot = $jackpot;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getBigWinner()
    {
        return $this->big_winner;
    }

    public function setBigWinner($big_winner)
    {
        $this->big_winner = $big_winner;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function createResult(array $resultData)
    {
        if (!is_a($this->lottery, '\EuroMillions\entities\Lottery')) {
            throw new MissingRelationException('Cannot set a result without loading the lottery dependency (lottery name needed)');
        }
        $result_entity_name = '\EuroMillions\entities\\'.$this->lottery->getName().'Result';
        $this->result = new $result_entity_name();
        $this->result->initialize($resultData);
    }
}