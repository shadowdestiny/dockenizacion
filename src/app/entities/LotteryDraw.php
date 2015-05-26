<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;

class LotteryDraw implements IEntity
{
    protected $draw_id;
    protected $draw_date;
    protected $jackpot;
    protected $message;
    protected $big_winner;
    protected $published;
    protected $lottery;

    public function getLottery()
    {
        return $this->lottery;
    }

    public function setLottery($lottery)
    {
        $this->lottery = $lottery;
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
}