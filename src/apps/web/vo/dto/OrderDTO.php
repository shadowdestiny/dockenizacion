<?php


namespace EuroMillions\web\vo\dto;


use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Order;

class OrderDTO extends DTOBase implements IDto
{

    private $order;
    public $total;
    public $fee_limit;
    public $fee;
    public $single_bet_price;
    public $wallet_balance;
    public $play_config_list;
    public $lines;
    public $startDrawDate;
    public $lastDrawDate;
    public $drawDays;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->exChangeObject();
    }

    public function toArray()
    {

    }

    public function exChangeObject()
    {
        $this->total = ($this->order->getTotal()) ? $this->order->getTotal()->getAmount() / 10000 : 0;
        $this->fee_limit = $this->order->getFeeLimit()->getAmount() / 100;
        $this->fee = $this->order->getFee()->getAmount() / 100;
        $this->single_bet_price = $this->order->getSingleBetPrice()->getAmount() / 10000;
        $this->wallet_balance = $this->order->getPlayConfig()->getUser()->getBalance()->getAmount() / 100;
        $this->lines = $this->euroMillionsLinesToJson();
        $this->startDrawDate = $this->order->getPlayConfig()->getStartDrawDate();
        $this->lastDrawDate = $this->order->getPlayConfig()->getLastDrawDate();
        $this->drawDays = $this->order->getPlayConfig()->getDrawDays()->value();
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return mixed
     */
    public function getFeeLimit()
    {
        return $this->fee_limit;
    }

    /**
     * @param mixed $fee_limit
     */
    public function setFeeLimit($fee_limit)
    {
        $this->fee_limit = $fee_limit;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return mixed
     */
    public function getSingleBetPrice()
    {
        return $this->single_bet_price;
    }

    /**
     * @param mixed $single_bet_price
     */
    public function setSingleBetPrice($single_bet_price)
    {
        $this->single_bet_price = $single_bet_price;
    }

    /**
     * @return mixed
     */
    public function getWalletBalance()
    {
        return $this->wallet_balance;
    }

    /**
     * @param mixed $wallet_balance
     */
    public function setWalletBalance($wallet_balance)
    {
        $this->wallet_balance = $wallet_balance;
    }

    /**
     * @return mixed
     */
    public function getPlayConfigList()
    {
        return $this->play_config_list;
    }

    /**
     * @param mixed $play_config_list
     */
    public function setPlayConfigList($play_config_list)
    {
        $this->play_config_list = $play_config_list;
    }

    /**
     * @return mixed
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param mixed $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * @return mixed
     */
    public function getStartDrawDate()
    {
        return $this->startDrawDate;
    }

    /**
     * @param mixed $startDrawDate
     */
    public function setStartDrawDate($startDrawDate)
    {
        $this->startDrawDate = $startDrawDate;
    }

    /**
     * @return mixed
     */
    public function getDrawDays()
    {
        return $this->drawDays;
    }

    /**
     * @param mixed $drawDays
     */
    public function setDrawDays($drawDays)
    {
        $this->drawDays = $drawDays;
    }


    /**
     * @return mixed
     */
    public function getLastDrawDate()
    {
        return $this->lastDrawDate;
    }

    /**
     * @param mixed $lastDrawDate
     */
    public function setLastDrawDate($lastDrawDate)
    {
        $this->lastDrawDate = $lastDrawDate;
    }



    private function euroMillionsLinesToJson()
    {
        $lines = $this->order->getPlayConfig()->getLine();
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

}