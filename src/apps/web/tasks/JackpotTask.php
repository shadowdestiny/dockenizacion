<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\entities\User;
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
        $jackpot_amount = $this->lotteriesDataService->getNextJackpot('EuroMillions');
        /** @var ActionResult $users_reminder */
        $result = $this->userService->getAllUsersWithJackpotReminder();
        if($result->success()) {
            /** @var User[] $users_reminder */
            $users_reminder = $result->getValues();
            foreach($users_reminder as $user) {
                if($jackpot_amount->getAmount() >= $user->getBalance()->getAmount()) {
                    $this->emailService->sendTransactionalEmail($user,'jackpot-rollover');
                }
            }
        }
    }
}