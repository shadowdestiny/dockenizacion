<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsDrawBreakDownData;
use Money\Currency;
use Money\Money;

class EuroMillionsDrawBreakDownDataDTO extends DTOBase implements IDto
{

    private $euroMillionsDrawBreakDownDataDTO;

    public $name;

    public $lottery_prize;

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


    public function exChangeObject()
    {
        $this->setName($this->euroMillionsDrawBreakDownDataDTO->getName());
        $this->setLotteryPrize($this->euroMillionsDrawBreakDownDataDTO->getLotteryPrize()->getAmount());
        $this->setWinners($this->euroMillionsDrawBreakDownDataDTO->getWinners());
        $corrected = explode("+",trim($this->getName()));
        $this->setNumbersCorrected($corrected[0]);
        $this->setStarsCorrected($corrected[1]);
    }

    public function toArray()
    {
        throw new UnsupportedOperationException('Method not implemented');
    }

}