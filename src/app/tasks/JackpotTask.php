<?php
namespace EuroMillions\tasks;
use Doctrine\ORM\EntityManager;
use EuroMillions\entities\User;
use EuroMillions\services\CurrencyService;
use EuroMillions\services\EmailService;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LanguageService;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\services\UserService;
use EuroMillions\vo\ActionResult;
use Phalcon\CLI\Task;
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