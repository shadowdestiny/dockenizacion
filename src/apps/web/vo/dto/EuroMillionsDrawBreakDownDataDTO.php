<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\exceptions\UnsupportedOperationException;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsDrawBreakDownData;

class EuroMillionsDrawBreakDownDataDTO extends DTOBase implements IDto
{

    private $euroMillionsDrawBreakDownDataDTO;

    public $name;

    public $lottery_prize;

    public $winners;

    public $winners_formatted;

    public $numbers_corrected;

    public $stars_corrected;

    public $nameCategory;


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
        $this->setLotteryPrize(trim($this->euroMillionsDrawBreakDownDataDTO->getLotteryPrize()->getAmount()));
        $this->setWinners(trim($this->euroMillionsDrawBreakDownDataDTO->getWinners()));
        if($this->getName() != null) {
            $corrected = explode("+",trim($this->getName()));
            $this->setNumbersCorrected((int) $corrected[0]);
            $this->setStarsCorrected((int) $corrected[1]);
        }
        $this->setNameCategory('');
    }

    public function toArray()
    {
        throw new UnsupportedOperationException('Method not implemented');
    }


    public function toJson()
    {
        return json_encode(json_decode(json_encode($this),TRUE));
    }

    public function getWinnersWithFormat()
    {
        $this->winners_formatted = str_replace(',','',trim($this->euroMillionsDrawBreakDownDataDTO->getWinners()));
    }

    /**
     * @return mixed
     */
    public function getNameCategory()
    {
        return $this->nameCategory;
    }

    /**
     * @param mixed $nameCategory
     */
    public function setNameCategory($nameCategory)
    {
        $this->nameCategory = $nameCategory;
    }


    private function nameCategory($key)
    {
        $categories =  [
            52 => 'category_one',
            51 => 'category_two',
            50 => 'category_three',
            42 => 'category_four',
            41 => 'category_five',
            40 => 'category_six',
            32 => 'category_seven',
            22 => 'category_eight',
            31 => 'category_nine',
            30 => 'category_ten',
            12 => 'category_eleven',
            21 => 'category_twelve',
            20 => 'category_thirteen'
        ];
        return $categories[preg_replace('/\s+/', '', str_replace('+','',$key))];
    }

}