<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PrizeCheckoutService;
use EuroMillions\web\services\factories\ServiceFactory;
use Doctrine\ORM\UnexpectedResultException;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Money;

class RaffleTask extends TaskBase
{
    const EMAIL_ABOVE = 2500 * 100;

    /** @var  PrizeCheckoutService */
    private $PrizeCheckoutService;

    /** @var  LotteryService */
    private $lotteryService;

    public function initialize(PrizeCheckoutService $PrizeCheckoutService = null, LotteryService $lotteryService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->PrizeCheckoutService = $PrizeCheckoutService ? $this->PrizeCheckoutService = $PrizeCheckoutService : $domainFactory->getPrizeCheckoutService();
        $this->lotteryService = $lotteryService ?: $this->lotteryService = $domainFactory->getLotteryService();
        parent::initialize();
    }

    public function checkoutAction($args = 'now')
    {
        $today = new \DateTime($args[0]);
        if (!$today) {
            $today = new \DateTime();
        }

        $drawDate = $this->lotteryService->getLastDrawDate('EuroMillions', $today);
        $lottery_name = 'EuroMillions';
        $play_configs_result_awarded = $this->PrizeCheckoutService->playConfigsWithBetsAwarded($drawDate);
        //get breakdown
        $result_breakdown = $this->lotteryService->getLastDrawWithBreakDownByDate($lottery_name, $drawDate);
        $euromillions_breakDown = $result_breakdown->getValues()->getBreakDown();
        $betsRaffle = $this->PrizeCheckoutService->getBetsRaffle($euromillions_breakDown, $drawDate);

        $lastRaffle = $this->lotteryService->getLastRaffle('EuroMillions', $today);
        if ($betsRaffle === $lastRaffle) {
//TODO: what I do if equals
        } else {

        }
    }

    public function saveRaffleAction(\DateTime $today = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }
        try {
            $this->lotteryService->addRaffle('Euromillions', $today);
        } catch (\Exception $e) {
            throw new UnexpectedResultException();
        }
    }
}
