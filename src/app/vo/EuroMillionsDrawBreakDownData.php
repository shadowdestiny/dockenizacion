<?php


namespace EuroMillions\vo;


class EuroMillionsDrawBreakDownData
{

    protected $name;

    protected $lottery_prizes;

    protected $winners;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;

    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLotteryPrizes()
    {
        return $this->lottery_prizes;
    }

    /**
     * @param mixed $lottery_prizes
     */
    public function setLotteryPrizes($lottery_prizes)
    {
        $this->lottery_prizes = $lottery_prizes;
    }

    /**
     * @return mixed
     */
    public function getWinners()
    {
        return $this->winners;
    }

    /**
     * @param mixed $winners
     */
    public function setWinners($winners)
    {
        $this->winners = $winners;
    }

}