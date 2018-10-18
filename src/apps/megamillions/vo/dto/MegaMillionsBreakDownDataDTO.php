<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:36
 */

namespace EuroMillions\megamillions\vo\dto;


class MegaMillionsBreakDownDataDTO
{

    public  $name;

    public  $megaMillionsPrize;

    public $winnersMegaMillions;

    public $winnersMegaplier;

    public $megaplierPrize;

    public $showMegaMillions;


    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->megaMillionsPrize = $data['megaMillionsPrize'];
        $this->winnersMegaMillions = $data['winnersMegaMillions'];
        $this->winnersMegaplier = $data['winnersMegaplier'];
        $this->megaplierPrize = $data['megaplierPrize'];
        $this->showMegaMillions = $data['showMegaMillions'];
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
    public function getMegaMillionsPrize()
    {
        return $this->megaMillionsPrize;
    }

    /**
     * @param mixed $megaMillionsPrize
     */
    public function setMegaMillionsPrize($megaMillionsPrize)
    {
        $this->megaMillionsPrize = $megaMillionsPrize;
    }

    /**
     * @return mixed
     */
    public function getWinnersMegaMillions()
    {
        return $this->winnersMegaMillions;
    }

    /**
     * @param mixed $winnersMegaMillions
     */
    public function setWinnersMegaMillions($winnersMegaMillions)
    {
        $this->winnersMegaMillions = $winnersMegaMillions;
    }

    /**
     * @return mixed
     */
    public function getWinnersMegaPlier()
    {
        return $this->winnersMegaplier;
    }

    /**
     * @param mixed $winnersMegaPlier
     */
    public function setWinnersPowerPlay($winnersMegaPlier)
    {
        $this->winnersMegaplier = $winnersMegaPlier;
    }

    /**
     * @return mixed
     */
    public function getMegaPlierPrize()
    {
        return $this->megaplierPrize;
    }

    /**
     * @param mixed $megaPlierPrize
     */
    public function setPowerPlayPrize($megaPlierPrize)
    {
        $this->megaplierPrize = $megaPlierPrize;
    }



}