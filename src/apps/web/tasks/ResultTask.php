<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\NotificationType;
use Money\Currency;
use Money\Money;
use Phalcon\Di;

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
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        ($userService) ? $this->userService = $userService : $domainFactory->getUserService();
        ($currencyService) ? $this->currencyService = $currencyService : $domainFactory->getServiceFactory()->getCurrencyService();
        parent::initialize();
    }


    public function updateAction(\DateTime $today = null)
    {
        if(!$today) {
            $today = new \DateTime();
        }

        $this->lotteriesDataService->updateLastDrawResult('EuroMillions');
        $this->lotteriesDataService->updateLastBreakDown('EuroMillions');
        /** @var EuroMillionsDrawBreakDown $emBreakDownData */
        $draw = $this->lotteriesDataService->getBreakDownDrawByDate('EuroMillions',$today);
        $breakdown_list = null;
        if($draw->success()){
            $break_down_list = new EuroMillionsDrawBreakDownDTO($draw->getValues());
        }
        $result_play_config = $this->playService->getPlaysConfigToBet($today);
        if($result_play_config->success() && !empty($break_down_list)){
            /** @var PlayConfig[] $play_config_list */
            $play_config_list = $result_play_config->getValues();
            foreach($play_config_list as $play_config){
                /** @var User $user */
                $user = $this->userService->getUser($play_config->getUser()->getId());
                $user_notifications_result = $this->userService->getActiveNotificationsByUserAndType($user, NotificationType::NOTIFICATION_RESULT_DRAW);
                if($user_notifications_result->success()) {
                    /** @var UserNotifications $user_notification */
                    $user_notification = $user_notifications_result->getValues();
                    if($user_notification->getActive() && $user_notification->getConfigValue()) {
                        $this->emailService->sendTransactionalEmail($user,'latest-results');
                    }
                }
            }
        }

        $user_notifications_result = $this->userService->getActiveNotificationsByType(NotificationType::NOTIFICATION_RESULT_DRAW);
        if($user_notifications_result->success()) {
            $users_notifications = $user_notifications_result->getValues();
            foreach($users_notifications as $user_notification) {
                if(!$user_notification->getConfigValue()) {
                    $this->emailService->sendTransactionalEmail($user_notification->getUser(),'latest-results');
                }
            }
        }
    }

    //EMTD convert in service instead?
    private function convertCurrency($break_downs, Currency $user_currency = null)
    {
        if(empty($user_currency)){
            $user_currency = $this->userService->getCurrency();
        }
        foreach($break_downs as $k => $name) {
            $new_currency_prize = $this->currencyService->convert(new Money((int) $break_downs[$k]['lottery_prize'], new Currency('EUR')), $user_currency);
            $break_downs[$k]['lottery_prize'] = $new_currency_prize->getAmount() / 10000;
        }
        return $break_downs;
    }
}