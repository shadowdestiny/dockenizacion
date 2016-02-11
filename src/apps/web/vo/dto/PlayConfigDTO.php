<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Currency;
use Money\Money;

class PlayConfigDTO extends DTOBase implements IDto
{

    private $playConfig;

    public $regular_numbers;

    public $lucky_numbers;

    /** @var  DrawDays */
    public $drawDays;

    public $startDrawDate;

    public $lastDrawDate;

    /** @var  User $user */
    public $user;

    public $duration;

    public $lines;

    public $wallet_balance_user;

    public $play_config_total_amount;

    public $single_bet_price;




    public function __construct(PlayConfig $playConfig, Money $single_bet_price = null)
    {
        $this->playConfig = $playConfig;
        $this->single_bet_price = $single_bet_price ?: $single_bet_price;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {

        //EMTD refactor when lines are more than one
        $last = $this->playConfig->getLastDrawDate();
        $start = $this->playConfig->getStartDrawDate();
        $this->lastDrawDate = $last->format('Y-m-d');
        $this->startDrawDate = $start->format('Y-m-d');
        if(count($this->playConfig->getLine()) > 1 ) {
            $this->lines = $this->euroMillionsLinesToJson();
            $this->regular_numbers = [];
            $this->lucky_numbers = [];
        } else {
            $this->regular_numbers = str_replace(',',' ', $this->playConfig->getLine()->getRegularNumbers());
            $this->lucky_numbers = str_replace(',',' ', $this->playConfig->getLine()->getLuckyNumbers());
        }
        $this->drawDays = $this->playConfig->getDrawDays()->value();
        $this->lines = $this->euroMillionsLinesToJson();
        $this->duration = $this->getFormatDuration();
        $this->user = $this->playConfig->getUser();
        $this->wallet_balance_user = $this->playConfig->getUser()->getBalance();
        $result_total = count($this->playConfig->getLine()) * $this->playConfig->getDrawDays()->value() * $this->single_bet_price->getAmount() / 100;
        $this->play_config_total_amount = new Money((int) $result_total, new Currency('EUR'));
    }

    public function toArray()
    {
        return $array = json_decode(json_encode($this),TRUE);
    }

    private function euroMillionsLinesToJson()
    {
        $lines = $this->playConfig->getLine();
        if( null == $lines ) {
            return [];
        }
        $euromillionsLines = [];
        /** @var EuroMillionsLine $line */
        foreach($lines as $k => $line) {
            $euromillionsLines['bets'][$k]['regular'] = $line->getRegularNumbers();
            $euromillionsLines['bets'][$k]['lucky'] = $line->getLuckyNumbers();
        }
        return json_encode($euromillionsLines);
    }

    private function getFormatDuration()
    {

        $last = $this->playConfig->getLastDrawDate();
        $start = $this->playConfig->getStartDrawDate();
        $duration = '0 weeks';

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