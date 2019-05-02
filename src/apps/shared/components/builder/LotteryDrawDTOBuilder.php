<?php


namespace EuroMillions\shared\components\builder;


use EuroMillions\eurojackpot\vo\dto\EuroJackpotDrawDTO;
use EuroMillions\megamillions\vo\dto\MegaMillionsDrawDTO;
use EuroMillions\megasena\vo\dto\MegaSenaDrawDTO;
use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawDTO;
use EuroMillions\web\vo\dto\PowerBallDrawDTO;

final class LotteryDrawDTOBuilder
{

    /**
     * @var EuroMillionsDraw $euromillionsDraw
     */
    private $euromillionsDraw;

    /**
     * @var EmTranslationAdapter $emTranslationAdapter
     */
    private $emTranslationAdapter;

    private $lotteryName;

    private $euromillionsDrawBreakDownDTO;

    private $mapper = [
        'EuroJackpot' => 'EuroMillions\eurojackpot\vo\dto\\EuroJackpotDrawDTO',
        'MegaSena'    => 'EuroMillions\megasena\vo\dto\\MegaSenaDrawDTO',
        'SuperEnalotto'    => 'EuroMillions\superenalotto\vo\dto\\SuperEnalottoDrawDTO',
        'PowerBall'   => 'EuroMillions\web\vo\dto\\PowerBallDrawDTO',
        'MegaMillions' => 'EuroMillions\megamillions\vo\dto\\MegaMillionsDrawDTO'
    ];


    public function __construct()
    {

    }

    public function build()
    {
        $drawDTO = $this->mapper[$this->lotteryName];
        return new $drawDTO($this->euromillionsDraw,$this->emTranslationAdapter);
    }



    /**
     * @param EuroMillionsDraw $euromillionsDraw
     */
    public function setEuromillionsDraw($euromillionsDraw)
    {
        $this->euromillionsDraw = $euromillionsDraw;
        return $this;
    }

    /**
     * @param EmTranslationAdapter $emTranslationAdapter
     */
    public function setEmTranslationAdapter($emTranslationAdapter)
    {
        $this->emTranslationAdapter = $emTranslationAdapter;
        return $this;
    }

    /**
     * @param mixed $lotteryName
     */
    public function setLotteryName($lotteryName)
    {
        $this->lotteryName = $lotteryName;
        return $this;
    }

    /**
     * @param mixed $euromillionsDrawBreakDownDTO
     */
    public function setEuromillionsDrawBreakDownDTO($euromillionsDrawBreakDownDTO)
    {
        $this->euromillionsDrawBreakDownDTO = $euromillionsDrawBreakDownDTO;
    }

}