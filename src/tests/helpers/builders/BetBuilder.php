<?php
namespace EuroMillions\tests\helpers\builders;

use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;

class BetBuilder
{
    protected $playConfig;

    protected $euromillionsDraw;

    public function __construct(PlayConfig $playConfig, EuroMillionsDraw $euroMillionsDraw)
    {
        $this->playConfig = $playConfig;
        $this->euromillionsDraw = $euroMillionsDraw;
    }

    public function build()
    {
        return new Bet($this->playConfig, $this->euromillionsDraw);
    }

}