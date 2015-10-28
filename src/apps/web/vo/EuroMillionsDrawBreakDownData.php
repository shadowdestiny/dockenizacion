<?php


namespace EuroMillions\web\vo;


class EuroMillionsDrawBreakDownData
{

    protected $name;

    protected $lottery_prize;

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
    public function getLotteryPrize()
    {
        return $this->lottery_prize;
    }

    /**
     * @param mixed $lottery_prize
     */
    public function setLotteryPrize($lottery_prize)
    {
        $this->lottery_prize = $lottery_prize;
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