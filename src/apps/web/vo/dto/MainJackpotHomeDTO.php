<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 23/11/18
 * Time: 9:14
 */

namespace EuroMillions\web\vo\dto;


use EuroMillions\eurojackpot\vo\EuroJackpotJackpot;
use EuroMillions\megamillions\vo\MegaMillionsJackpot;
use EuroMillions\shared\interfaces\IComparable;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\interfaces\IDto;
use EuroMillions\web\interfaces\IJackpot;
use EuroMillions\web\vo\dto\base\DTOBase;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\PowerBallJackpot;
use Money\Money;

class MainJackpotHomeDTO extends DTOBase implements IDto,IComparable
{


    public $link;

    public $css;

    /** @var IJackpot jackpot */
    public $jackpot;

    public $lotteryName;

    public $drawDate;

    public $includeSlide;

    public $drawDateFormat;


    private function __construct(EuroMillionsDraw $euroMillionsDraw)
    {
        $this->lotteryName = $euroMillionsDraw->getLottery()->getName();
        $this->drawDate = $euroMillionsDraw->getDrawDate();
        $this->link = $this->giveMeConfigByLottery()[$this->lotteryName]['link'];
        $this->css = $this->giveMeConfigByLottery()[$this->lotteryName]['css'];
        $this->jackpot = $euroMillionsDraw->getJackpot() instanceof IJackpot ? $euroMillionsDraw->getJackpot() : $this->IJackpot($euroMillionsDraw->getJackpot());
        $this->includeSlide = $this->giveMeConfigByLottery()[$this->lotteryName]['include'];
        $this->drawDateFormat = $euroMillionsDraw->getDrawDate()->format("Y-m-d H:i:s");
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
                    'css'  => 'lotteries-jackpot--bar--euromillions',
                    'include' => '_elements/home/lottery-carousel/euromillions'

            ],
            'PowerBall' => [
                    'link' => 'link_powerball_play',
                    'css'  => 'lotteries-jackpot--bar--powerball',
                    'include' => '_elements/home/lottery-carousel/powerball'
            ],
            'MegaMillions' => [
                    'link' => 'link_megamillions_play',
                    'css'  => 'lotteries-jackpot--bar--megamillions',
                    'include' => '_elements/home/lottery-carousel/megamillions'
            ],
            'EuroJackpot' => [
                'link' => 'link_eurojackpot_play',
                'css'  => 'lotteries-jackpot--bar--eurojackpot',
                'include' => '_elements/home/lottery-carousel/eurojackpot'
            ],
            'MegaSena' => [
                'link' => 'link_megasena_play',
                'css'  => 'lotteries-jackpot--bar--megasena',
                'include' => '_elements/home/lottery-carousel/megasena'
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
        if($this->lotteryName == 'EuroJackpot')
        {
            return EuroJackpotJackpot::fromAmountIncludingDecimals($amount->getAmount());
        }
        if($this->lotteryName == 'MegaSena')
        {
            return EuroJackpotJackpot::fromAmountIncludingDecimals($amount->getAmount());
        }
    }

    public function compare(IComparable $object)
    {
        return $this->lotteryName === $object->lotteryName;
    }
}