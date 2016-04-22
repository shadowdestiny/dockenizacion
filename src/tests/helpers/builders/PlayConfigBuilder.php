<?php


namespace EuroMillions\tests\helpers\builders;


use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;

use EuroMillions\web\entities\User;
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
    protected $days;
    protected $startDrawDate;
    protected $lastDrawDate;
    protected $active;
    protected $threshold;
    protected $frequency;
    protected $lottery;
    
    const REGULAR_NUMBERS = [7, 16, 17, 22, 15];

    const LUCKY_NUMBERS = [7, 1];

    public function __construct()
    {
        $this->line = $this->getLine();
        $this->days = new DrawDays(2);
        $this->startDrawDate = new \DateTime('2015-09-10');
        $this->lastDrawDate = new \DateTime('2015-09-30');
        $this->active = 1;
        $this->lottery = $this->getLottery();
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


    private function getLine()
    {
        $reg = self::REGULAR_NUMBERS;
        $regular_numbers = [];
        foreach ($reg as $regular_number) {
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = self::LUCKY_NUMBERS;
        $lucky_numbers = [];
        foreach ($luck as $lucky_number) {
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }
        return new EuroMillionsLine($regular_numbers, $lucky_numbers);
    }

    private function getLottery()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
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


}