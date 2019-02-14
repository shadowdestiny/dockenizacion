<?php
namespace EuroMillions\web\entities;

use EuroMillions\eurojackpot\vo\EuroJackpotDrawBreakDown;
use EuroMillions\eurojackpot\vo\MegaSenaDrawBreakDown;
use EuroMillions\megamillions\vo\MegaMillionsDrawBreakDown;
use EuroMillions\megasena\vo\MegaSenaLine;
use EuroMillions\web\interfaces\IEntity;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\PowerBallDrawBreakDown;
use EuroMillions\web\vo\Raffle;
use Money\Money;

class EuroMillionsDraw extends EntityBase implements IEntity
{
    protected $id;

    protected $draw_date;
    /** @var  Money $jackpot */
    protected $jackpot;
    /** @var  Lottery */
    protected $lottery;
    /** @var  EuroMillionsLine */
    protected $result;
    /** @var  EuroMillionsDrawBreakDown */
    protected $break_down;
    /** @var  Raffle */
    protected $raffle;

    /**
     * @return Raffle
     */
    public function getRaffle()
    {
        return $this->raffle;
    }

    /**
     * @param Raffle $raffle
     */
    public function setRaffle($raffle)
    {
        $this->raffle = $raffle;
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
            $lucky_numbers[] = new EuroMillionsLuckyNumber((int) $number);
        }
        if($this->lottery->isMegaSena())
        {
            $this->result = new MegaSenaLine($regular_numbers);
        }
        else
        {
            $this->result = new EuroMillionsLine($regular_numbers, $lucky_numbers);
        }

    }

    public function createBreakDown(array $result)
    {
        if ($this->lottery->getName() == 'MegaMillions') {
            $className=MegaMillionsDrawBreakDown::class;
            $breakDowns=[
                'prizes' => $result['prizes'],
                'winners' => $result['winners']
            ];
        }
        elseif ($this->lottery->getName() == 'PowerBall')
        {
            $className=PowerBallDrawBreakDown::class;
            $breakDowns=[
                'prizes' => $result['prizes'],
                'winners' => $result['winners']
            ];
        }
        elseif ($this->lottery->getName() == 'EuroJackpot')
        {
            $className=EuroJackpotDrawBreakDown::class;
            $breakDowns=[
                'prizes' => $result['prizes'],
                'winners' => $result['winners']
            ];
        }
        elseif ($this->lottery->getName() == 'MegaSena')
        {
            $className=MegaSenaDrawBreakDown::class;
            $breakDowns=[
                'prizes' => $result['prizes'],
                'winners' => $result['winners']
            ];
        }
        else{
            $className=EuroMillionsDrawBreakDown::class;
            $breakDowns=$result;
        }
        $euroMilliosnBreakDownData = new $className($breakDowns);
        $this->setBreakDown($euroMilliosnBreakDownData);
    }

    public function hasBreakDown()
    {
        return (
            null !== $this->getBreakDown()->getCategoryOne()->getName()
        ) ? true : false;
    }

}