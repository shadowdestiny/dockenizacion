<?php
namespace EuroMillions\tests\helpers\builders;

use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use Money\Money;

class EuroMillionsDrawBuilder
{
    const DEFAULT_ID = 1;
    const DEFAULT_DRAW_DATE = '2016-04-22';

    private $id;
    private $lottery;
    private $draw_date;
    private $jackpot;
    private $break_down;
    private $result;

    public static function aDraw()
    {
        return new EuroMillionsDrawBuilder();
    }

    public function __construct()
    {
        $this->id = self::DEFAULT_ID;
        $this->lottery = LotteryMother::anEuroMillions();
        $this->draw_date = self::DEFAULT_DRAW_DATE;
    }

    public function withJackpot(Money $jackpot)
    {
        $this->jackpot = $jackpot;
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

    public function build()
    {
        $draw = new EuroMillionsDraw();
        $draw->initialize(get_object_vars($this));
        return $draw;
    }
}