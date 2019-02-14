<?php
namespace EuroMillions\megasena\tasks;

use EuroMillions\web\tasks\TaskBase;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\UserService;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;

class JackpotTask extends TaskBase
{
    /** @var  LotteriesDataService */
    protected $lotteriesDataService;
    /** @var  LotteryService */
    protected $lotteryService;
    /** @var  UserService */
    protected $userService;
    /** @var  EmailService */
    protected $emailService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, LotteryService $lotteryService = null, UserService $userService = null, EmailService $emailService = null)
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ?: $this->domainServiceFactory->getLotteriesDataService();
        $this->userService = $userService ?: $this->domainServiceFactory->getUserService();
        $this->emailService = $emailService ?: $this->domainServiceFactory->getServiceFactory()->getEmailService();
        $this->lotteryService = $lotteryService ?: $this->domainServiceFactory->getLotteryService();
    }

    public function updateNextDrawJackpotEuroJackpotAction()
    {
        $this->lotteriesDataService->updateNextDrawJackpotLottery('MegaSena');

    }

    public function updatePreviousAction(\DateTime $today = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        /** @var \DateTime $date */
        $date = $this->lotteryService->getLastDrawDate('MegaSena', $today);
        $this->lotteriesDataService->updateNextDrawJackpotLottery('MegaSena', $date->sub(new \DateInterval('PT1M')));
    }

    public function reminderJackpotAction()
    {
        $jackpot_amount = $this->lotteryService->getNextJackpot('MegaSena');
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new JackpotRolloverEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService));
        /** @var ActionResult $result */
        $result = $this->userService->getActiveNotificationsTypeJackpot();

        if($result->success()) {
            /** @var UserNotifications[] $user_notifications */
            $user_notifications = $result->getValues();
            foreach($user_notifications as $user_notification) {
                if($user_notification->getActive()) {
                    $valueThreshold = new Money((int)$user_notification->getConfigValue()->getValue() * 100, new Currency('EUR'));
                    if($jackpot_amount->getAmount() >= $valueThreshold->getAmount()) {
                        $user = $this->userService->getUser($user_notification->getUser()->getId());
                        $emailTemplate->setUser($user);
                        $emailTemplate->setThresholdAmount($user_notification->getConfigValue()->getValue());
                        $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                    }
                }
            }
        }
    }
}