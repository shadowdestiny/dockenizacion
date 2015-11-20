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
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDataDTO;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
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

    private $break_down_json;

    private $draw_result;

    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null, CurrencyService $currencyService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        ($currencyService) ? $this->currencyService = $currencyService : $domainFactory->getServiceFactory()->getCurrencyService();
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
                    /** @var UserNotifications[] $user_notifications */
                    $user_notifications = $user_notifications_result->getValues();
                    foreach($user_notifications as $user_notification) {
                        if($user_notification->getActive() && $user_notification->getConfigValue()->getValue()) {
                            $vars = $this->getVarsToEmailTemplate($break_down_list);
                            $this->emailService->sendTransactionalEmail($user,'latest-results', $vars);
                        }
                    }
                }
            }
        }

        $user_notifications_result = $this->userService->getActiveNotificationsByType(NotificationType::NOTIFICATION_RESULT_DRAW);
        if($user_notifications_result->success()) {
            $users_notifications = $user_notifications_result->getValues();
            foreach($users_notifications as $user_notification) {
                if(!$user_notification->getConfigValue()->getValue()) {
                    $vars = $this->getVarsToEmailTemplate($break_down_list);
                    $this->emailService->sendTransactionalEmail($user_notification->getUser(),'latest-results',$vars);
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

    private function getVarsToEmailTemplate($break_down_dto_list)
    {

        if(empty($this->break_down_json)) {
            $break_down_json = [];
            /** @var EuroMillionsDrawBreakDownDataDTO[] $break_down_dto_list */
            foreach($break_down_dto_list as $break_down) {
                $break_down_json[] = $break_down->toJson();
            }
            $this->break_down_json = $break_down_json;
            /** @var EuroMillionsLine $draw_result */
            $this->draw_result = $this->lotteriesDataService->getLastResult('EuroMillions');
        }

        //vars email template
        $vars = [
            'subject' => 'Last results',
            'template_vars' =>
                [
                    [
                        'name'    => 'break_down',
                        'content' => json_encode($this->break_down_json)
                    ],
                    [
                        'name'    => 'regular_numbers',
                        'content' => $this->draw_result['regular_numbers']
                    ],
                    [
                        'name'    => 'lucky_numbers',
                        'content' => $this->draw_result['lucky_numbers']
                    ]
                ]
        ];

        return $vars;
    }
}