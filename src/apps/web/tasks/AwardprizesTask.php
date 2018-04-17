<?php

namespace EuroMillions\web\tasks;

use EuroMillions\web\emailTemplates\CheckAwardPrizesEmailTemplate;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\services\PrizeCheckoutService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use Money\Money;

class AwardprizesTask extends TaskBase
{
    const EMAIL_ABOVE = 2500 * 100;

    /** @var  PrizeCheckoutService */
    private $PrizeCheckoutService;

    /** @var  LotteryService */
    private $lotteryService;

    /** @var  EmailService $emailService */
    protected $emailService;

    /** @var  TransactionService $transactionService */
    protected $transactionService;

    public function initialize(PrizeCheckoutService $PrizeCheckoutService = null, LotteryService $lotteryService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->PrizeCheckoutService = $PrizeCheckoutService ? $this->PrizeCheckoutService = $PrizeCheckoutService : $domainFactory->getPrizeCheckoutService();
        $this->lotteryService = $lotteryService ?: $this->lotteryService = $domainFactory->getLotteryService();
        $serviceFactory = new ServiceFactory($this->getDI());
        $this->emailService = $serviceFactory->getEmailService();
        parent::initialize();
    }

    public function checkoutAction($args = 'now')
    {
        $this->transactionService->getWinningTransactions();
        $playConfigsAwarded = [];
        $usersAwarded = 0;
        $today = new \DateTime($args[0]);
        if (!$today) {
            $today = new \DateTime();
        }
        $drawDate = $this->lotteryService->getLastDrawDate('EuroMillions', $today);
        $lottery_name = 'EuroMillions';

        $play_configs_result_awarded = $this->PrizeCheckoutService->playConfigsWithBetsAwarded($drawDate);
        //get breakdown
        $result_breakdown = $this->lotteryService->getLastDrawWithBreakDownByDate($lottery_name, $drawDate);
        if ($result_breakdown->success() && $play_configs_result_awarded->success()) {
            /** @var EuroMillionsDrawBreakDown $euromillions_breakDown */
            $euromillions_breakDown = $result_breakdown->getValues()->getBreakDown();
            $play_configs_awarded = $play_configs_result_awarded->getValues();
//            $this->sendEmailCheckAwardedPrizes($play_configs_result_awarded->getValues(), 1);
            foreach ($play_configs_awarded as $k => $play_config_and_count) {

                if (isset($play_config_and_count['cnt']) && isset($play_config_and_count['cnt_lucky'])) {
                    $playConfigsAwarded[] = $play_config_and_count;
                    /** @var Money $result_amount */
                    $result_amount = $euromillions_breakDown->getAwardFromCategory($play_config_and_count['cnt'], $play_config_and_count['cnt_lucky']);
                    $scalarValues = [
                        'matches' => ['cnt' => $play_config_and_count['cnt'], 'cnt_lucky' => $play_config_and_count['cnt_lucky']],
                        'userId' => $play_config_and_count['userId'],
                        'playConfigId' => $play_config_and_count['playConfig']
                    ];
                    if ($result_amount->getAmount() > 0) {
                        $this->PrizeCheckoutService->awardUser($play_config_and_count[0], $result_amount, $scalarValues);
                        $this->PrizeCheckoutService->matchNumbersUser($play_config_and_count[0], $scalarValues, $drawDate, $result_amount);
                        $usersAwarded++;
                    }
                }
            }
        }
        $this->sendEmailCheckAwardedPrizes($playConfigsAwarded, $usersAwarded);
    }

    public function sendEmailCheckAwardedPrizes($play_configs_result_awarded, $usersAwarded)
    {
        $usersList = [];
        foreach ($play_configs_result_awarded as $playConfigs) {
            $usersList[] = $playConfigs['userId'];
        }
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new CheckAwardPrizesEmailTemplate($emailBaseTemplate, new JackpotDataEmailTemplateStrategy());
        $emailTemplate->setUsersPlayed(count($play_configs_result_awarded));
        $emailTemplate->setUsersAwarded($usersAwarded);
        $emailTemplate->setUsers($usersList);

        $user = new User();
        $user->setEmail(new Email('alerts@panamedia.net'));
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }
}