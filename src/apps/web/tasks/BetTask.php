<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\IEmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\NotificationValue;

class BetTask extends TaskBase
{
    /** @var  LotteryService */
    private $lotteryService;

    /** @var  PlayService */
    private $playService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;

    public function initialize(LotteryService $lotteryService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        $this->lotteryService = $lotteryService ?: $domainFactory->getLotteryService();
        ($lotteryService) ? $this->lotteryService = $lotteryService : $this->lotteryService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
    }


    public function placeBets()
    {
        $lotteries = $this->lotteryService->getLotteriesOrderedByNextDrawDate();
        foreach( $lotteries as $lottery ) {
            $this->lotteryService->placeBetForNextDraw($lottery);
        }
    }


    public function longTermNotificationAction(\DateTime $today = null)
    {
        if(!$today) {
            $today = new \DateTime();
        }
        /** @var ActionResult $result_play_config */
        $result_play_config = $this->playService->getPlayConfigWithLongEnded($today);
        if($result_play_config->success()) {
            $this->userService->checkLongTermAndSendNotification($result_play_config->getValues(),$today);
        }
    }

    private function sendEmailAutoPlay(PlayConfig $playConfig, IEmailTemplate $emailTemplate)
    {
        $user = $this->userService->getUser($playConfig->getUser()->getId());
        $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationValue::NOTIFICATION_LAST_DRAW);
        if($user_notifications_result->success()) {
            /** @var UserNotifications $user_notification */
            $users_notifications = $user_notifications_result->getValues();
            foreach($users_notifications as $user_notification) {
                if ($user_notification->getActive()) {
                    $this->emailService->sendTransactionalEmail($user, $emailTemplate);
                }
            }
        }
    }
}