<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:36
 */

namespace EuroMillions\web\vo\dto;


class PowerBallBreakDownDataDTO
{

    public  $name;

    public  $powerBallPrize;

    public $winnersPowerBall;

    public $winnersPowerPlay;

    public $powerPlayPrize;


    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->powerBallPrize = $data['powerBallPrize'];
        $this->winnersPowerBall = $data['winnersPowerBall'];
        $this->winnersPowerPlay = $data['winnersPowerPlay'];
        $this->powerPlayPrize = $data['powerPlayPrize'];
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
    public function getPowerBallPrize()
    {
        return $this->powerBallPrize;
    }

    /**
     * @param mixed $powerBallPrize
     */
    public function setPowerBallPrize($powerBallPrize)
    {
        $this->powerBallPrize = $powerBallPrize;
    }

    /**
     * @return mixed
     */
    public function getWinnersPowerBall()
    {
        return $this->winnersPowerBall;
    }

    /**
     * @param mixed $winnersPowerBall
     */
    public function setWinnersPowerBall($winnersPowerBall)
    {
        $this->winnersPowerBall = $winnersPowerBall;
    }

    /**
     * @return mixed
     */
    public function getWinnersPowerPlay()
    {
        return $this->winnersPowerPlay;
    }

    /**
     * @param mixed $winnersPowerPlay
     */
    public function setWinnersPowerPlay($winnersPowerPlay)
    {
        $this->winnersPowerPlay = $winnersPowerPlay;
    }

    /**
     * @return mixed
     */
    public function getPowerPlayPrize()
    {
        return $this->powerPlayPrize;
    }

    /**
     * @param mixed $powerPlayPrize
     */
    public function setPowerPlayPrize($powerPlayPrize)
    {
        $this->powerPlayPrize = $powerPlayPrize;
    }



}