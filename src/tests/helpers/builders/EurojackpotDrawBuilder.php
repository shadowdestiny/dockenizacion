<?php
namespace EuroMillions\tests\helpers\builders;

use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\RaffleMother;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Raffle;
use Money\Money;

class EurojackpotDrawBuilder
{
    const DEFAULT_ID = 5;
    const DEFAULT_DRAW_DATE = '2016-04-22';

    private $id;
    private $lottery;
    private $draw_date;
    private $jackpot;
    private $break_down;
    private $result;
    private $raffle;

    public static function aDraw()
    {
        return new EurojackpotDrawBuilder();
    }

    public function __construct()
    {
        $this->id = self::DEFAULT_ID;
        $this->lottery = LotteryMother::aEuroJackpot();
        $this->draw_date = new \DateTime('2016-04-22');
        $this->raffle = RaffleMother::anRaffle();
    }

    public function withJackpot(Money $jackpot)
    {
        $this->jackpot = $jackpot;
        return $this;
    }

    public function withDrawDate(\DateTime $dateTime)
    {
        $this->draw_date= $dateTime;
        return $this;
    }

    public function withLottery(Lottery $lottery)
    {
        $this->lottery= $lottery;
        return $this;
    }

    public function withBreakDown(EuroMillionsDrawBreakDown $breakDown)
    {
        $this->break_down = $breakDown;
        return $this;
    }

    public function withResult(EuroMillionsLine $result)
    {
        $this->result = $result;
        return $this;
    }

    public function withId($id)
    {
        $this->id= $id;
        return $this;
    }

    public function build()
    {
        $draw = new EuroMillionsDraw();
        $draw->initialize(get_object_vars($this));
        return $draw;
    }

    public function withRaffle(Raffle $raffle)
    {
        $this->raffle = $raffle;
        return $this;
    }
}