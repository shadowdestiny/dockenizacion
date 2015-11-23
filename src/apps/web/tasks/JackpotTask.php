<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\ActionResult;
use Phalcon\Di;

class JackpotTask extends TaskBase
{
    /** @var  LotteriesDataService */
    protected $lotteriesDataService;
    /** @var  UserService */
    protected $userService;
    /** @var  EmailService */
    protected $emailService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, UserService $userService = null, EmailService $emailService = null)
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->emailService = $emailService ? $emailService : $this->domainServiceFactory->getServiceFactory()->getEmailService();
    }

    public function updateAction()
    {
        $this->lotteriesDataService->updateNextDrawJackpot('EuroMillions');
    }

    public function updatePreviousAction(\DateTime $today = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }

        /** @var \DateTime $date */
        $date = $this->lotteriesDataService->getLastDrawDate('EuroMillions', $today);
        $this->lotteriesDataService->updateNextDrawJackpot('EuroMillions', $date->sub(new \DateInterval('PT1M')));
    }

    public function reminderJackpotAction()
    {
        $next_draw_day = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $time_config = $this->getDI()->get('globalConfig')['retry_validation_time'];
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');
        $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');

        //vars email template
        $vars = [
            'subject' => 'Jackpot',
            'vars' =>
            [
                [
                    'name'    => 'jackpot',
                    'content' => $jackpot_amount->getAmount()/100
                ],
                [
                    'name'    => 'draw_day_format_one',
                    'content' => $draw_day_format_one
                ],
                [
                    'name'    => 'draw_day_format_two',
                    'content' => $draw_day_format_two,
                ],
                [
                    'name'    => 'time_closed',
                    'content' => $time_config['time'] . ' CET'
                ],
                [
                    'name'    => 'url_play',
                    'content' => $this->config->domain['url'] . 'play'
                ]
            ]
        ];

        /** @var ActionResult $result */
        $result = $this->userService->getActiveNotificationsTypeJackpot();

        if($result->success()) {
            /** @var UserNotifications[] $user_notifications */
            $user_notifications = $result->getValues();
            foreach($user_notifications as $user_notification) {
                if($user_notification->getActive()) {
                    if($jackpot_amount->getAmount() >= $user_notification->getConfigValue()->getValue()) {
                        $user = $this->userService->getUser($user_notification->getUser()->getId());
                        $this->emailService->sendTransactionalEmail($user,'jackpot-rollover',$vars);
                    }
                }
            }
        }
    }
}