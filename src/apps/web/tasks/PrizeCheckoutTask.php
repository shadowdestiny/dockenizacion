<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\entities\User;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PrizeCheckoutService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Money;

class PrizeCheckoutTask extends TaskBase
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
        $lottery_name = 'EuroMillions';
        $draw_date = $this->lotteryService->getLastDrawDate($lottery_name, $today);
        $play_configs_result_awarded = $this->PrizeCheckoutService->playConfigsWithBetsAwarded($draw_date);
        //get breakdown
        $result_breakdown = $this->lotteryService->getDrawWithBreakDownByDate($lottery_name, $today);
        if ($result_breakdown->success() && $play_configs_result_awarded->success()) {
            /** @var EuroMillionsDrawBreakDown $euromillions_breakDown */
            $euromillions_breakDown = $result_breakdown->getValues()->getBreakDown();
            $play_configs_awarded = $play_configs_result_awarded->getValues();
            foreach ($play_configs_awarded as $play_config_and_count) {

                /** @var Money $result_amount */
                $result_amount = $euromillions_breakDown->getAwardFromCategory($play_config_and_count['cnt'], $play_config_and_count['cnt_lucky']);
                if ($result_amount->getAmount() > 0) {
                    /** @var User $user */
                    $user = $play_config_and_count[0]->getUser();
                    $this->PrizeCheckoutService->awardUser($user, $result_amount);
                }
            }
        }
    }
}