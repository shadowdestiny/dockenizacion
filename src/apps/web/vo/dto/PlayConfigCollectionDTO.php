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

class PlayConfigCollectionDTO extends DTOBase implements IDto
{
    /** @var PlayConfig[] */
    private $playConfig;

    public $regular_numbers;

    public $lucky_numbers;

    /** @var  DrawDays */
    public $drawDays;

    public $startDrawDate;

    /** @var \DateTime */
    public $startDrawDateTime;

    public $lastDrawDate;

    /** @var  User $user */
    public $user;

    public $duration;
    /** @var  EuroMillionsLine[] $lines */
    public $lines;

    public $wallet_balance_user;

    public $play_config_total_amount;

    public $single_bet_price;

    public $single_bet_price_converted;

    public $singleBetPriceWithDiscount;

    public $singleBetPriceWithDiscountConverted;

    public $frequency;

    public $numPlayConfigs;


/// EMTD @rmrbest Why there's no unit test over this class? For example, drawDays is declared as DrawDays type, but then you assign an integer to it... WTF?

    public function __construct(array $playConfig, Money $single_bet_price)
    {
        $this->playConfig = $playConfig;
        $this->single_bet_price = $single_bet_price;
        $this->numPlayConfigs = count($playConfig);
        $this->exChangeObject();
    }

    public function exChangeObject()
    {
        $last = $this->playConfig[0]->getLastDrawDate();
        $start = $this->playConfig[0]->getStartDrawDate();
        $this->lastDrawDate = $last->format('Y-m-d');
        $this->startDrawDate = $start->format('Y M j');
        $this->startDrawDateTime = $start;
        $this->lines = $this->euroMillionsLinesToJson();
        $this->regular_numbers = [];
        $this->lucky_numbers = [];
        $this->lines = $this->euroMillionsLinesToJson();
        $this->duration_format = $this->getFormatDuration();
        $this->duration = $this->duration();
        $this->frequency = $this->playConfig[0]->getFrequency();
        $this->user = $this->playConfig[0]->getUser();
        $this->wallet_balance_user = $this->playConfig[0]->getUser()->getBalance();
        $this->singleBetPriceWithDiscount = $this->playConfig[0]->getSinglePrice();
        $result_total = count($this->playConfig) * ($this->single_bet_price->getAmount()) * $this->playConfig[0]->getFrequency();
        $this->play_config_total_amount = new Money((int) str_replace('.','',$result_total), new Currency('EUR')) ;
    }

    public function get($key)
    {
        $last = $this->playConfig[$key]->getLastDrawDate();
        $start = $this->playConfig[$key]->getStartDrawDate();
        $this->lastDrawDate = $last->format('Y-m-d');
        $this->startDrawDate = $start->format('Y M j');
        $this->lines = $this->euroMillionsLine($key);
        $this->regular_numbers = [];
        $this->lucky_numbers = [];
        $this->lines = $this->euroMillionsLine($key);
        $this->duration_format = $this->getFormatDuration();
        $this->duration = $this->duration();
        $this->frequency = $this->playConfig[$key]->getFrequency();
        $this->user = $this->playConfig[$key]->getUser();
        $this->wallet_balance_user = $this->playConfig[$key]->getUser()->getBalance();
        $result_total = count($this->playConfig) * ($this->single_bet_price->getAmount()) * $this->playConfig[$key]->getFrequency();
        $this->play_config_total_amount = new Money((int) str_replace('.','',$result_total), new Currency('EUR')) ;
        return $this;
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

    private function euroMillionsLine($key)
    {
        $euromillionsLines = [];
        $euromillionsLines['bets']['regular'] = $this->playConfig[$key]->getLine()->getRegularNumbers();
        $euromillionsLines['bets']['lucky'] = $this->playConfig[$key]->getLine()->getLuckyNumbers();
        return $euromillionsLines;
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
        return $last->format("W") - $start->format("W") == 0 ? 1 : $last->format("W") - $start->format("W");
    }

}