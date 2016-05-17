<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class EuroMillionsDrawDTO extends DTOBase implements IDto
{

    private $euroMillionsDraw;
    public $id;
    public $regularNumbers;
    public $regularNumbersArray;
    public $luckyNumbers;
    public $luckyNumbersArray;
    public $jackpot;
    public $drawDate;
    public $euroMillionsDrawBreakDownDTO;


    public function __construct( EuroMillionsDrawBreakDownDTO $euroMillionsDrawBreakDownDTO, EuroMillionsDraw $euroMillionsDraw )
    {
        $this->euroMillionsDrawBreakDownDTO = $euroMillionsDrawBreakDownDTO;
        $this->euroMillionsDraw = $euroMillionsDraw;
        $this->exChangeObject();
    }


    public function exChangeObject()
    {
        $this->id = $this->euroMillionsDraw->getId();
        $this->regularNumbers = $this->euroMillionsDraw->getResult()->getRegularNumbers();
        $this->regularNumbersArray = $this->euroMillionsDraw->getResult()->getRegularNumbersArray();
        $this->luckyNumbers = $this->euroMillionsDraw->getResult()->getLuckyNumbers();
        $this->luckyNumbersArray = $this->euroMillionsDraw->getResult()->getLuckyNumbersArray();
        $this->jackpot = $this->euroMillionsDraw->getJackpot()->getAmount();
        $this->drawDate = $this->euroMillionsDraw->getDrawDate();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}