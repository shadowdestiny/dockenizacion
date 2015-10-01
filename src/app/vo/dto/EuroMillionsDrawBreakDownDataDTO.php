<?php


namespace EuroMillions\vo\dto;


use EuroMillions\exceptions\UnsupportedOperationException;
use EuroMillions\vo\dto\base\DTOBase;
use EuroMillions\vo\EuroMillionsDrawBreakDownData;

class EuroMillionsDrawBreakDownDataDTO extends DTOBase
{

    private $euroMillionsDrawBreakDownDataDTO;

    public $name;

    public $lottery_prizes;

    public $winners;

    public $numbers_corrected;

    public $stars_corrected;


    public function __construct(EuroMillionsDrawBreakDownData $euroMillionsDrawBreakDownDataDTO)
    {
        $this->euroMillionsDrawBreakDownDataDTO = $euroMillionsDrawBreakDownDataDTO;
        $this->exchangeObject();
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


    protected function exChangeObject()
    {
        $this->setName($this->euroMillionsDrawBreakDownDataDTO->getName());
        $this->setLotteryPrizes($this->euroMillionsDrawBreakDownDataDTO->getLotteryPrizes());
        $this->setWinners($this->euroMillionsDrawBreakDownDataDTO->getWinners());
        $corrected = explode("+",trim($this->getName()));
        $this->setNumbersCorrected($corrected[0]);
        $this->setStarsCorrected($corrected[1]);
    }

    public function toArray()
    {
        throw new UnsupportedOperationException('Method no implemented');
    }
}