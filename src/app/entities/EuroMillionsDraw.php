<?php
namespace EuroMillions\entities;

use EuroMillions\interfaces\IEntity;
use EuroMillions\vo\EuroMillionsLuckyNumber;
use EuroMillions\vo\EuroMillionsRegularNumber;
use EuroMillions\vo\EuroMillionsLine;

class EuroMillionsDraw extends EntityBase implements IEntity
{
    protected $id;
    protected $draw_date;
    protected $jackpot;

    protected $published;
    /** @var  Lottery */
    protected $lottery;
    /** @var  EuroMillionsLine */
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

    public function getId()
    {
        return $this->id;
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

    public function createResult(array $regularNumbers, array $luckyNumbers)
    {
        $regular_numbers = [];
        $lucky_numbers = [];
        foreach ($regularNumbers as $number) {
            $regular_numbers[] = new EuroMillionsRegularNumber($number);
        }
        foreach ($luckyNumbers as $number) {
            $lucky_numbers[] = new EuroMillionsLuckyNumber($number);
        }
        $this->result = new EuroMillionsLine($regular_numbers, $lucky_numbers);
    }
}