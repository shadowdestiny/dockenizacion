<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\IEmailTemplate;
use EuroMillions\web\emailTemplates\LongPlayEndedEmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\email_templates_strategies\DataLotteryEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\NotificationType;

class BetTask extends TaskBase
{
    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  PlayService */
    private $playService;

    /** @var  EmailService */
    private $emailService;

    /** @var  UserService */
    private $userService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
    }

    public function createBetAction(\DateTime $today = null, $time_to_retry = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        $dateUtil = new DateTimeUtil();
        $is_check_time = $dateUtil->checkOpenTicket($time_to_retry);
        $lotteryName = 'EuroMillions';
        $result_euromillions_draw = $this->lotteriesDataService->getNextDrawByLottery($lotteryName);
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LowBalanceEmailTemplate($emailTemplate, new DataLotteryEmailTemplateStrategy());

        if($result_euromillions_draw->success()){
            /** @var EuroMillionsDraw $euromillions_draw */
            $euromillions_draw = $result_euromillions_draw->getValues();
            $result_play_configs = $this->playService->getPlaysConfigToBet($result_euromillions_draw->getValues()->getDrawDate());
        } else {
            $result_play_configs = new ActionResult(false);
        }

        if($result_play_configs->success()){
            try{
                if(empty($is_check_time)) {
                    throw new \Exception('');
                }
                /** @var PlayConfig[] $play_config_list */
                $play_config_list = $result_play_configs->getValues();
                /** @var User $user */
                $user = null;
                $user_id = '';
                $result_bet = null;
                foreach($play_config_list as $play_config) {
                    if( $play_config->getDrawDays()->compareTo($dateUtil->getDayOfWeek($euromillions_draw->getDrawDate())) ) {
                        if( empty( $play_config->getUser()->getThreshold() ) ){
                            try{
                                if(empty($user_id)){
                                    /** @var ActionResult $result_bet */
                                    $this->playService->bet($play_config, $euromillions_draw);
                                    $this->sendEmailAutoPlay($play_config,$emailTemplate);
                                }
                                if(!empty($user_id) && $user_id != $play_config->getUser()->getId()->id()){
                                    $user_id = '';
                                    /** @var ActionResult $result_bet */
                                    $this->playService->bet($play_config, $euromillions_draw);
                                    $this->sendEmailAutoPlay($play_config,$emailTemplate);
                                }
                            } catch( InvalidBalanceException $e ) {
                                if( empty($user_id) || $user_id != $play_config->getUser()->getId()->id() ) {
                                    $user = $this->userService->getUser($play_config->getUser()->getId());
                                    $user_id = $play_config->getUser()->getId()->id();
                                    $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationType::NOTIFICATION_NOT_ENOUGH_FUNDS);
                                    if( $user_notifications_result->success() ) {
                                        /** @var UserNotifications $user_notification */
                                        $users_notifications = $user_notifications_result->getValues();
                                        foreach($users_notifications as $user_notification) {
                                            if($user_notification->getActive()) {
                                                $this->emailService->sendTransactionalEmail($user, $emailTemplate);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if( $euromillions_draw->getJackpot()->getAmount() >= $play_config->getThreshold() ) {
                                $this->playService->bet($play_config, $euromillions_draw);
                            }
                        }
                    }
                }
            } catch( \Exception $e ) {
                $user_id = '';
                $play_config_list = $result_play_configs->getValues();
                /** @var PlayConfig[] $play_config */
                foreach($play_config_list as $play_config) {
                    if(empty($user_id) || $user_id != $play_config->getUser()->getId()->id()){
                        $user = $this->userService->getUser($play_config->getUser()->getId());
                        $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                        $user_id = $user->getId()->id();
                    }
                }
            }
        }
    }

    public function longTermNotificationAction(\DateTime $today = null)
    {
        if(!$today) {
            $today = new \DateTime();
        }

        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LongPlayEndedEmailTemplate($emailTemplate, new DataLotteryEmailTemplateStrategy());
        /** @var ActionResult $result_play_config */
        $result_play_config = $this->playService->getPlayConfigWithLongEnded($today);
        if($result_play_config->success()) {
            /** @var PlayConfig[] $play_config_list */
            $play_config_list = $result_play_config->getValues();
            foreach($play_config_list as $play_config) {
                $day_last_draw = $play_config->getLastDrawDate()->getTimestamp();
                if($today->getTimestamp() > $day_last_draw ) {
                    $user = $this->userService->getUser($play_config->getUser()->getId());
                    $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                }
            }
        }
    }

    private function sendEmailAutoPlay(PlayConfig $playConfig, IEmailTemplate $emailTemplate)
    {
        $user = $this->userService->getUser($playConfig->getUser()->getId());
        $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationType::NOTIFICATION_LAST_DRAW);
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