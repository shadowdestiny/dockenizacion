<?php
namespace EuroMillions\web\entities;

use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Money;

class EuroMillionsDraw extends EntityBase implements IEntity
{
    protected $id;
    protected $draw_date;
    /** @var  Money $jackpot */
    protected $jackpot;

    protected $published;
    /** @var  Lottery */
    protected $lottery;
    /** @var  EuroMillionsLine */
    protected $result;
    /** @var  EuroMillionsDrawBreakDown */
    protected $break_down;

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return mixed
     */
    public function getBreakDown()
    {
        return $this->break_down;
    }

    /**
     * @param mixed $break_down
     */
    public function setBreakDown($break_down)
    {
        $this->break_down = $break_down;
    }


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

    public function createBreakDown(array $breakDowns)
    {
        $euroMilliosnBreakDownData = new EuroMillionsDrawBreakDown($breakDowns);
        $this->setBreakDown($euroMilliosnBreakDownData);
    }
}