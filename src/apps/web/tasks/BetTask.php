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
        //Llamamos a LotteryService::getLotteriesOrderedByNextDrawDate
        //foreach lotteries
        //LotteryService::placeBetsForNextDraw(Lottery)
        //endforeach
    }



    public function createBetAction(\DateTime $today = null, $time_to_retry = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }
        $dateUtil = new DateTimeUtil();
        $is_check_time = $dateUtil->checkOpenTicket($time_to_retry);


        $lotteryName = 'EuroMillions';
        $result_euromillions_draw = $this->lotteryService->getNextDrawByLottery($lotteryName);
        $emailTemplate = new EmailTemplate();
        $emailTemplate = new LowBalanceEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy());

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
                        if( empty( $play_config->getThreshold() ) ){ //EMTD @rmrbest GetThreshold en el usuario no tenía sentido porque no está definido en la entidad User, sino en PlayConfig. ¿¿Esto había funcionado alguna vez??
                            try{
                                if(empty($user_id)){
                                    /** @var ActionResult $result_bet */
                                    $this->playService->bet($play_config, $euromillions_draw);
                                    $this->sendEmailAutoPlay($play_config,$emailTemplate);
                                }
                                if(!empty($user_id) && $user_id != $play_config->getUser()->getId()){
                                    $user_id = '';
                                    /** @var ActionResult $result_bet */
                                    $this->playService->bet($play_config, $euromillions_draw);
                                    $this->sendEmailAutoPlay($play_config,$emailTemplate);
                                }
                            } catch( InvalidBalanceException $e ) {
                                if( empty($user_id) || $user_id != $play_config->getUser()->getId() ) {
                                    $user = $this->userService->getUser($play_config->getUser()->getId());
                                    $user_id = $play_config->getUser()->getId();
                                    $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationValue::NOTIFICATION_NOT_ENOUGH_FUNDS);
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
                /** @var PlayConfig[] $play_config_list */
                foreach($play_config_list as $play_config) {
                    if(empty($user_id) || $user_id != $play_config->getUser()->getId()){
                        $user = $this->userService->getUser($play_config->getUser()->getId());
                        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
                        $user_id = $user->getId();
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