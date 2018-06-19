<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 18/06/18
 * Time: 11:34
 */

namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class PowerBallDrawDTO extends DTOBase implements IDto
{

    /** @var PowerBallDrawBreakDownDTO $powerballDrawBreakDownDTO */
    protected $powerballDrawBreakDownDTO;

    protected $resultNumbers;

    protected $luckyNumber;

    protected $powerPlayNumber;

    protected $euromillionsDraw;


    public function __construct(EuroMillionsDraw $euroMillionsDraw)
    {
        $this->euromillionsDraw = $euroMillionsDraw;
        $this->exChangeObject();
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function exChangeObject()
    {
        $this->resultNumbers = $this->euromillionsDraw->getResult()->getRegularNumbers();
        $this->luckyNumber = $this->euromillionsDraw->getResult()->getLuckyNumbers();
        $this->powerPlayNumber = $this->euromillionsDraw->getRaffle()->getValue();
        $this->powerballDrawBreakDownDTO = new PowerBallDrawBreakDownDTO($this->euromillionsDraw->getBreakDown());
    }
}