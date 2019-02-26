<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 26/02/19
 * Time: 13:26
 */

namespace EuroMillions\megasena\vo\dto;


class MegaSenaBreakDownDataDTO
{

    public $name;

    public $megaSenaPrize;

    public $winnersMegaSena;

    public $showMegaSena;


    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->megaSenaPrize = $data['megaSenaPrize'];
        $this->winnersMegaSena = $data['winnersMegaSena'];
        $this->showMegaSena = $data['showMegaSena'];
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
    public function getMegaSenaPrize()
    {
        return $this->megaSenaPrize;
    }

    /**
     * @param mixed $megaSenaPrize
     */
    public function setMegaSenaPrize($megaSenaPrize)
    {
        $this->megaSenaPrize = $megaSenaPrize;
    }

    /**
     * @return mixed
     */
    public function getWinnersMegaSena()
    {
        return $this->winnersMegaSena;
    }

    /**
     * @param mixed $winnersMegaSena
     */
    public function setWinnersMegaSena($winnersMegaSena)
    {
        $this->winnersMegaSena = $winnersMegaSena;
    }
}