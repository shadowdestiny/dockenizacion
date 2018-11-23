<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 23/11/18
 * Time: 9:14
 */

namespace EuroMillions\web\vo\dto;


use EuroMillions\megamillions\vo\MegaMillionsJackpot;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\interfaces\IJackpot;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\PowerBallJackpot;
use Money\Money;

class MainJackpotHomeDTO extends DTOBase implements IDto
{


    public $link;

    public $css;

    /** @var IJackpot jackpot */
    public $jackpot;

    public $lotteryName;

    public $drawDate;


    private function __construct(EuroMillionsDraw $euroMillionsDraw)
    {
        $this->lotteryName = $euroMillionsDraw->getLottery()->getName();
        $this->drawDate = $euroMillionsDraw->getDrawDate();
        $this->link = $this->giveMeConfigByLottery()[$this->lotteryName]['link'];
        $this->css = $this->giveMeConfigByLottery()[$this->lotteryName]['css'];
        $this->jackpot = $this->IJackpot($euroMillionsDraw->getJackpot());

    }


    public static function mainJAckpotHomeDTO(EuroMillionsDraw $euroMillionsDraw)
    {
        return new static($euroMillionsDraw);
    }


    public function exChangeObject()
    {

    }

    public function toArray()
    {

    }


    private function giveMeConfigByLottery()
    {
        return [
            'EuroMillions' => [
                    'link' => 'link_euromillions_play',
                    'css'  => 'lotteries-jackpot--bar--euromillions'
            ],
            'PowerBall' => [
                    'link' => 'link_powerball_play',
                    'css'  => 'lotteries-jackpot--bar--powerball'
            ],
            'MegaMillions' => [
                    'link' => 'link_megamillions_play',
                    'css'  => 'lotteries-jackpot--bar--megamillions'
            ]
        ];
    }

    private function IJackpot(Money $amount)
    {
        if($this->lotteryName == 'EuroMillions')
        {
            return EuroMillionsJackpot::fromAmountIncludingDecimals($amount->getAmount());
        }
        if($this->lotteryName == 'PowerBall')
        {
            return PowerBallJackpot::fromAmountIncludingDecimals($amount->getAmount());
        }
        if($this->lotteryName == 'MegaMillions')
        {
            return MegaMillionsJackpot::fromAmountIncludingDecimals($amount->getAmount());
        }
    }

}