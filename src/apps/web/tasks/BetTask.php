<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\ActionResult;
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
        ($userService) ? $this->userService = $userService : $this->domainServiceFactory->getUserService();
    }

    public function createBetAction(\DateTime $today = null, $time_to_retry = null)
    {
        if (!$today) $today = new \DateTime();

        if(!$time_to_retry) strtotime('now');
        $time_config = $this->getDI()->get('globalConfig')['retry_validation_time'];
        $date_today = new \DateTime();
        $limit_time = strtotime($date_today->format('Y/m/d '. $time_config['time']));
        $is_check_time = ($limit_time< $time_to_retry) ? true : false;

        $lotteryName = 'EuroMillions';
        $result_euromillions_draw = $this->lotteriesDataService->getNextDrawByLottery($lotteryName);
        /** @var EuroMillionsDraw $euromillions_draw */
        $euromillions_draw = $result_euromillions_draw->getValues();
        $result_play_configs = $this->playService->getPlaysConfigToBet($result_euromillions_draw->getValues()->getDrawDate());

        if($result_play_configs->success()){
            if($is_check_time) {
                /** @var PlayConfig[] $play_config_list */
                $play_config_list = $result_play_configs->getValues();
                /** @var User $user */
                $user = null;
                $user_id = '';
                $result_bet = null;
                foreach($play_config_list as $play_config) {
                    if($play_config->getDrawDays()->compareTo($euromillions_draw->getDrawDate()->format('w'))){
                        if(empty($play_config->getUser()->getThreshold())){
                            try{
                                if(empty($user_id)){
                                    /** @var ActionResult $result_bet */
                                    $this->playService->bet($play_config, $euromillions_draw);
                                    $this->sendEmailAutoPlay($play_config);
                                }
                                if(!empty($user_id) && $user_id != $play_config->getUser()->getId()->id()){
                                    $user_id = '';
                                    /** @var ActionResult $result_bet */
                                    $this->playService->bet($play_config, $euromillions_draw);
                                    $this->sendEmailAutoPlay($play_config);
                                }
                            }catch(InvalidBalanceException $e){
                                if(empty($user_id) || $user_id != $play_config->getUser()->getId()->id()){
                                    $user = $this->userService->getUser($play_config->getUser()->getId());
                                    $user_id = $play_config->getUser()->getId()->id();
                                    $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationType::NOTIFICATION_NOT_ENOUGH_FUNDS);
                                    if($user_notifications_result->success()) {
                                        /** @var UserNotifications $user_notification */
                                        $user_notification = $user_notifications_result->getValues();
                                        if($user_notification->getActive()) {
                                            $this->emailService->sendTransactionalEmail($user, 'low-balance');
                                        }
                                    }
                                }
                            }
                        } else {
                            if($euromillions_draw->getJackpot()->getAmount() >= $play_config->getThreshold()) {
                                $this->playService->bet($play_config, $euromillions_draw);
                            }
                        }
                    }
                }
            }else {
                $user_id = '';
                $play_config_list = $result_play_configs->getValues();
                /** @var PlayConfig[] $play_config */
                foreach($play_config_list as $play_config) {
                    if(empty($user_id) || $user_id != $play_config->getUser()->getId()->id()){
                        $user = $this->userService->getUser($play_config->getUser()->getId());
                        //EMTD change name template to new template with information about validation ticket
                        $this->emailService->sendTransactionalEmail($user,'low-balance');
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

        /** @var ActionResult $result_play_config */
        $result_play_config = $this->playService->getPlaysConfigToBet($today);
        if($result_play_config->success()) {
            /** @var PlayConfig[] $play_config_list */
            $play_config_list = $result_play_config->getValues();
            foreach($play_config_list as $play_config) {
                $day_last_draw = $play_config->getLastDrawDate()->getTimestamp();
                if($today->getTimestamp() > $day_last_draw ) {
                    $user = $this->userService->getUser($play_config->getUser()->getId());
                    $this->emailService->sendTransactionalEmail($user,'long-play-is-ended');
                }
            }
        }
    }

    private function sendEmailAutoPlay(PlayConfig $playConfig)
    {
        $user = $this->userService->getUser($playConfig->getUser()->getId());
        $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationType::NOTIFICATION_LAST_DRAW);
        if($user_notifications_result->success()) {
            /** @var UserNotifications $user_notification */
            $user_notification = $user_notifications_result->getValues();
            if($user_notification->getActive()) {
                $this->emailService->sendTransactionalEmail($user, 'low-balance');
            }
        }
    }
}