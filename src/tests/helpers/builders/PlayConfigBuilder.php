<?php


namespace EuroMillions\tests\helpers\builders;


use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;

use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Money\Currency;
use Money\Money;

class PlayConfigBuilder
{
    protected $id;
    protected $user;
    protected $line;
    protected $startDrawDate;
    protected $lastDrawDate;
    protected $active;
    protected $frequency;
    protected $lottery;
    protected $discount;
    
    const REGULAR_NUMBERS = [7, 16, 17, 22, 15];

    const LUCKY_NUMBERS = [7, 1];

    public function __construct()
    {
        $this->id = 1;
        $this->line = $this->getLine();
        $this->startDrawDate = new \DateTime('2015-09-10');
        $this->lastDrawDate = new \DateTime('2015-09-30');
        $this->active = 1;
        $this->lottery = $this->getLottery();
        $this->discount = new Discount(0,[]);
    }

    public static function aPlayConfig()
    {
        return new PlayConfigBuilder();
    }

    public function withUser( User $user )
    {
        $this->user = $user;
        return $this;
    }
    
    public function withId( $id )
    {
        $this->id = $id;
        return $this;
    }

    public function withDays( DrawDays $drawDays )
    {
        $this->days = $drawDays->value();
        return $this;
    }

    public function withStartDrawDate( \DateTime $date )
    {
        $this->startDrawDate = $date;
        return $this;
    }

    public function withLastDrawDate( \DateTime $date )
    {
        $this->lastDrawDate = $date;
        return $this;
    }

    public function withNoActive()
    {
        $this->active = 0;
        return $this;
    }

    public function withLineFromArrays(array $regularNumbers, array $luckyNumbers)
    {
        $this->line = $this->createLineFromArrays($regularNumbers, $luckyNumbers);
        return $this;
    }

    public function withLine(EuroMillionsLine $line)
    {
        $this->line = $line;
        return $this;
    }

    public function withLottery($lottery)
    {
        $this->lottery = $this->getLottery($lottery);
        return $this;
    }

    private function getLine()
    {
        $reg = self::REGULAR_NUMBERS;
        $luck = self::LUCKY_NUMBERS;
        return $this->createLineFromArrays($reg, $luck);
    }

    private function getLottery($lotteryId = null)
    {
        if (is_null($lotteryId)) {
            $lotteryId = 1;
        }
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => $lotteryId,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
            'single_bet_price' => new Money( 250, new Currency('EUR')),
        ]);
        return $lottery;
    }

    /**
     * @return PlayConfig
     */
    public function build()
    {
        $playConfig = new PlayConfig();
        $playConfig->initialize(get_object_vars($this));
        return $playConfig;
    }

    public function toArray()
    {
        return $this->build()->toArray();
    }

    /**
     * @param $reg
     * @param $luck
     * @return EuroMillionsLine
     */
    private function createLineFromArrays($reg, $luck)
    {
        $regular_numbers = [];
        foreach ($reg as $regular_number) {
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $lucky_numbers = [];
        foreach ($luck as $lucky_number) {
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }
        return new EuroMillionsLine($regular_numbers, $lucky_numbers);
    }


}