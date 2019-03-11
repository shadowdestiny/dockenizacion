<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:36
 */

namespace EuroMillions\eurojackpot\vo\dto;


class EuroJackpotBreakDownDataDTO
{

    public $name;

    public $lottery_prize;

    public $winners;

    public $numbers_corrected;

    public $stars_corrected;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->lottery_prize = $data['lottery_prize'];
        $this->winners = $data['winners'];
        $this->numbers_corrected = $data['numbers_corrected'];
        $this->stars_corrected = $data['stars_corrected'];
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

    /**
     * @return mixed
     */
    public function getStarsCorrected()
    {
        return $this->stars_corrected;
    }

    /**
     * @param mixed $stars_corrected
     */
    public function setStarsCorrected($stars_corrected)
    {
        $this->stars_corrected = $stars_corrected;
    }


    /**
     * @return mixed
     */
    public function getNumbersCorrected()
    {
        return $this->numbers_corrected;
    }

    /**
     * @param mixed $numbers_corrected
     */
    public function setNumbersCorrected($numbers_corrected)
    {
        $this->numbers_corrected = $numbers_corrected;
    }
}