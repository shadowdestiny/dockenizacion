<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class EuroMillionsDrawDTO extends DTOBase implements IDto
{

    private $euroMillionsDraw;
    public $regularNumbers;
    public $luckyNumbers;
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
        $this->regularNumbers = $this->euroMillionsDraw->getResult()->getRegularNumbers();
        $this->luckyNumbers = $this->euroMillionsDraw->getResult()->getLuckyNumbers();
        $this->jackpot = $this->euroMillionsDraw->getJackpot()->getAmount();
        $this->drawDate = $this->euroMillionsDraw->getDrawDate();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}