<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;

class TicketPurchaseDetailDTO extends DTOBase implements IDto
{


    protected $playConfig;
    public $regularNumbers;
    public $luckyNumbers;
    public $drawDate;

    public function __construct(PlayConfig $playConfig)
    {
        $this->playConfig = $playConfig;
        $this->exChangeObject();
    }

    public function toArray()
    {
        throw new \Exception('Method not implemented');
    }

    public function exChangeObject()
    {
        $this->regularNumbers = $this->playConfig->getLine()->getRegularNumbers();
        $this->luckyNumbers = $this->playConfig->getLine()->getLuckyNumbers();
        $this->drawDate = $this->playConfig->getStartDrawDate()->format('Y-m-d');
    }

    public function toJson()
    {
        return json_encode(get_object_vars($this));
    }
}