<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:36
 */

namespace EuroMillions\eurojackpot\vo\dto;


class EurojackpotBreakDownDataDTO
{

    public $name;

    public $euroJackpotPrize;

    public $winnersEuroJackpot;

    public $showEuroJackpot;


    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->EuroJackpotPrize = $data['euroJackpotPrize'];
        $this->winnersEuroJackpot = $data['winnersEuroJackpot'];
        $this->showEuroJackpot = $data['showEuroJackpot'];
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
    public function getEuroJackpotPrize()
    {
        return $this->euroJackpotPrize;
    }

    /**
     * @param mixed $euroJackpotPrize
     */
    public function setEuroJackpotPrize($euroJackpotPrize)
    {
        $this->euroJackpotPrize = $euroJackpotPrize;
    }

    /**
     * @return mixed
     */
    public function getWinnersEurojackpot()
    {
        return $this->winnersEuroJackpot;
    }

    /**
     * @param mixed $winnersEuroJackpot
     */
    public function setWinnersEuroJackpot($winnersEuroJackpot)
    {
        $this->winnersEuroJackpot = $winnersEuroJackpot;
    }
}