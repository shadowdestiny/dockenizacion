<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\factories\DomainServiceFactory;

use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\NotificationType;
use Phalcon\Di;
use Phalcon\Logger;

class ResultTask extends TaskBase
{
    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  PlayService */
    private $playService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;

    /** @var  CurrencyService */
    private $currencyService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null, CurrencyService $currencyService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->currencyService =  $currencyService ? $currencyService : $domainFactory->getCurrencyService();
    }


    public function updateAction(\DateTime $today = null)
    {
        if(!$today) {
            $today = new \DateTime();
        }

        //EMTD refactor, DI instead -> loggerService
//        $config = $this->di->get('config');
//        $smsAlert = new TextMagicSmsWrapper(['username' => $config->sms['username'],
//                                             'password' => $config->sms['password']
//                                            ]);
//
//        $logger = new SmsAdapter('updateResults', $smsAlert, [$config->sms['number']]);
//        $logger->setLogLevel(Logger::ERROR);
        try{
            $this->lotteriesDataService->updateLastDrawResult('EuroMillions');
            $draw = $this->lotteriesDataService->updateLastBreakDown('EuroMillions');
        } catch( \Exception $e ) {
//            $logger->error($e->getMessage());
        }

        $breakdown_list = null;
        $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getBreakDown());

        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LatestResultsEmailTemplate($emailTemplate, new LatestResultsDataEmailTemplateStrategy());
       // $emailTemplate->setBreakDownList($break_down_list);

        $result_play_config = $this->playService->getPlaysConfigToBet($today);
        if($result_play_config->success() && !empty($break_down_list)){
            /** @var PlayConfig[] $play_config_list */
            $play_config_list = $result_play_config->getValues();
            foreach($play_config_list as $play_config){
                /** @var User $user */
                $user = $this->userService->getUser($play_config->getUser()->getId());
                $emailTemplate->setBreakDownList($break_down_list/*$this->convertCurrency($break_down_list,$user->getBalance()->getCurrency())*/);
                //$break_down_list->convertCurrency($user->getBalance()->getCurrency());
                $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationType::NOTIFICATION_RESULT_DRAW);
                if($user_notifications_result->success()) {
                    /** @var UserNotifications[] $user_notifications */
                    $user_notifications = $user_notifications_result->getValues();
                    foreach($user_notifications as $user_notification) {
                        if($user_notification->getActive() && !$user_notification->getConfigValue()->getValue()) {
                            $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                        }
                    }
                }
            }
        }

        $user_notifications_result = $this->userService->getActiveNotificationsByType(NotificationType::NOTIFICATION_RESULT_DRAW);
        if($user_notifications_result->success()) {
            $users_notifications = $user_notifications_result->getValues();
            foreach($users_notifications as $user_notification) {
                if($user_notification->getConfigValue()->getValue()) {
                    $emailTemplate->setBreakDownList($break_down_list/*$this->convertCurrency($break_down_list,$user->getBalance()->getCurrency())*/);
                    $this->emailService->sendTransactionalEmail($user_notification->getUser(),$emailTemplate);
                }
            }
        }
    }
}