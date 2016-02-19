<?php


namespace EuroMillions\web\vo\dto;


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
    /** @var  EuroMillionsLine[] $lines */
    public $lines;

    public $wallet_balance_user;

    public $play_config_total_amount;

    public $single_bet_price;

    public $frequency;




    public function __construct(array $playConfig, Money $single_bet_price = null)
    {
        $this->playConfig = $playConfig;
        $this->single_bet_price = $single_bet_price ?: $single_bet_price;
        $this->exChangeObject();
    }

    public function exChangeObject()
    {

        $last = $this->playConfig[0]->getLastDrawDate();
        $start = $this->playConfig[0]->getStartDrawDate();
        $this->lastDrawDate = $last->format('Y-m-d');
        $this->startDrawDate = $start->format('Y-m-d');
        $this->lines = $this->euroMillionsLinesToJson();
        $this->regular_numbers = [];
        $this->lucky_numbers = [];
//        } else {
//            $this->regular_numbers = str_replace(',',' ', $this->playConfig->getLine()[0]->getRegularNumbers());
//            $this->lucky_numbers = str_replace(',',' ', $this->playConfig->getLine()[0]->getLuckyNumbers());
//        }
        $this->drawDays = $this->playConfig[0]->getDrawDays()->value_len();
        $this->lines = $this->euroMillionsLinesToJson();
        $this->duration_format = $this->getFormatDuration();
        $this->duration = $this->duration();
        $this->frequency = $this->playConfig[0]->getFrequency();
        $this->user = $this->playConfig[0]->getUser();
        $this->wallet_balance_user = $this->playConfig[0]->getUser()->getBalance();
        $result_total = count($this->playConfig) * $this->playConfig[0]->getDrawDays()->value_len() * ($this->single_bet_price->getAmount() / 100) * $this->playConfig[0]->getFrequency();
        $this->play_config_total_amount = new Money(str_replace('.','',number_format($result_total,2,'.',',')) * 1, new Currency('EUR'));
    }

    public function toArray()
    {
        return $array = json_decode(json_encode($this),TRUE);
    }

    private function euroMillionsLinesToJson()
    {
        $euromillionsLines = [];
        if( is_array($this->playConfig) ) {
            foreach($this->playConfig as $k => $play_config) {
                $euromillionsLines['bets'][$k]['regular'] = $play_config->getLine()->getRegularNumbers();
                $euromillionsLines['bets'][$k]['lucky'] = $play_config->getLine()->getLuckyNumbers();
            }
        }
        return json_encode($euromillionsLines);
    }

    private function getFormatDuration()
    {

        $last = $this->playConfig[0]->getLastDrawDate();
        $start = $this->playConfig[0]->getStartDrawDate();
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

    private function duration()
    {
        $last = $this->playConfig[0]->getLastDrawDate();
        $start = $this->playConfig[0]->getStartDrawDate();
        return $last->format("W") - $start->format("W");
    }

}