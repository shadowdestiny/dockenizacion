<?php


namespace EuroMillions\vo\dto;


use EuroMillions\entities\PlayConfig;
use EuroMillions\vo\dto\base\DTOBase;

class PlayConfigDTO extends DTOBase
{

    private $playConfig;

    public $regular_numbers;

    public $lucky_numbers;

    public $drawDays;

    public $startDrawDate;

    public $lastDrawDate;

    public $duration;

    public function __construct(PlayConfig $playConfig)
    {
        $this->playConfig = $playConfig;
        $this->exChangeObject();
    }

    protected function exChangeObject()
    {
        $last = $this->playConfig->getLastDrawDate();
        $start = $this->playConfig->getStartDrawDate();
        $this->lastDrawDate = $last->format('Y-m-d');
        $this->startDrawDate = $start->format('Y-m-d');
        $this->regular_numbers = str_replace(',',' ',$this->playConfig->getLine()->getRegularNumbers());
        $this->lucky_numbers = str_replace(',',' ',$this->playConfig->getLine()->getLuckyNumbers());
        $this->drawDays = $this->playConfig->getDrawDays();
        $this->duration = $this->getFormatDuration();
    }

    public function toArray()
    {
        return $array = json_decode(json_encode($this),TRUE);
    }

    private function getFormatDuration()
    {

        $last = $this->playConfig->getLastDrawDate();
        $start = $this->playConfig->getStartDrawDate();

        $result = $last->format("W") - $start->format("W");

        switch($result){
            case 1:
                $duration = "1 week"; //EMTD put next draw day
                break;
            case 2:
                $duration = "2 weeks";
                break;
            case 4:
                $duration = "4 weeks";
                break;
            case 8:
                $duration = "8 weeks";
                break;
            case 52:
                $duration = "52 weeks";
                break;
            default:
                break;
        }

        return $duration;
    }

}