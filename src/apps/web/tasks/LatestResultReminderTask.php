<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\BetService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\user_notifications_strategies\UserNotificationResultsStrategy;
use EuroMillions\web\services\UserNotificationsService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;

class LatestResultReminderTask extends TaskBase
{

    /** @var  LotteryService */
    private $lotteryService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;

    /** @var  BetService */
    private $betService;

    /** @var  UserNotificationsService */
    private $userNotificationService;

    public function initialize(LotteryService $lotteryService = null,
                               EmailService $emailService = null,
                               UserService $userService = null,
                               BetService $betService = null,
                               UserNotificationsService $userNotificationsService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->lotteryService = $lotteryService ? $lotteryService : $this->domainServiceFactory->getLotteryService();
        $this->betService = $betService ?: $this->domainServiceFactory->getBetService();
        $this->userNotificationService = $userNotificationsService ?: $this->domainServiceFactory->getUserNotificationsService();
    }


    public function resultsReminderWhenPlayedAction( IEmailTemplateDataStrategy $IEmailTemplateDataStrategy = null)
    {
        if($IEmailTemplateDataStrategy == null ) {
            $IEmailTemplateDataStrategy = new LatestResultsDataEmailTemplateStrategy();
        }
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryService->getLastDrawDate('EuroMillions');
        $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getBreakDown());
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LatestResultsEmailTemplate($emailTemplate, $IEmailTemplateDataStrategy);
        $emailTemplate->setBreakDownList($break_down_list);
        $notificationResultsStrategy = new UserNotificationResultsStrategy($this->userService);
        $result = $this->betService->getBetsPlayedLastDraw($draw->getDrawDate());
        if (null != $result) {
            /** @var Bet $bet */
            foreach ($result as $bet) {
                $user = $bet->getPlayConfig()->getUser();
                $hasNotification = $this->userNotificationService->hasNotificationActive($notificationResultsStrategy, $user);
                if ($hasNotification === 0) {
                    $this->emailService->sendTransactionalEmail($user, $emailTemplate);
                }
            }
        }
        $users = $this->userService->getAllUsers();
        /** @var User $user */
        foreach($users as $user) {
            $hasNotification = $this->userNotificationService->hasNotificationActive($notificationResultsStrategy, $user);
            if (null != $hasNotification && $hasNotification) {
                $this->emailService->sendTransactionalEmail($user, $emailTemplate);
            }
        }
    }
}