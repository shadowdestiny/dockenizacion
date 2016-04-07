<?php


namespace EuroMillions\tests\helpers\builders;


use EuroMillions\web\entities\PlayConfig;

use EuroMillions\web\entities\User;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;

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


    public function __construct()
    {
        $this->line = $this->getLine();
        $this->days = new DrawDays(2);
        $this->startDrawDate = new \DateTime('2015-09-10');
        $this->lastDrawDate = new \DateTime('2015-09-30');
        $this->active = 1;
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
        $reg = [7, 16, 17, 22, 15];
        $regular_numbers = [];
        foreach ($reg as $regular_number) {
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = [7, 1];
        $lucky_numbers = [];
        foreach ($luck as $lucky_number) {
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }
        return new EuroMillionsLine($regular_numbers, $lucky_numbers);
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