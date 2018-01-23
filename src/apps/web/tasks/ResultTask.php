<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\factories\DomainServiceFactory;

use EuroMillions\web\services\email_templates_strategies\LatestResultsDataEmailTemplateStrategy;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\NotificationValue;
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

    /** @var LotteryService */
    private $lotteryService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null, EmailService $emailService = null, UserService $userService = null, CurrencyService $currencyService = null, LotteryService $lotteryService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        ($emailService) ? $this->emailService = $emailService : $this->emailService = $domainFactory->getServiceFactory()->getEmailService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->currencyService =  $currencyService ? $currencyService : $domainFactory->getCurrencyService();
        $this->lotteryService = $lotteryService ? $lotteryService : $this->domainServiceFactory->getLotteryService();
    }

    public function mainAction()
    {

    }

    public function updateAction(\DateTime $today = null)
    {
        $results = $this->lotteryService->getLastDrawWithBreakDownByDate('EuroMillions', $this->lotteryService->getLastDrawDate('Euromillions'));
        $resultNumbers = $results->returnValues()->getResult()->getRegularNumbers();
        $breakdown = $results->returnValues()->getBreakDown()->getCategoryOne();
        if (!$resultNumbers || $resultNumbers == ',,,,' || !$breakdown) {
            try {
                $this->lotteriesDataService->updateLastDrawResult('EuroMillions');
                $this->lotteriesDataService->updateLastBreakDown('EuroMillions');
            } catch (\Exception $e) {
                $name = 'Breakdown is Empty';
                $type = '';
                $message = 'Breakdown is not saved correctly, is empty or have failed.';
                $time = $now = new \DateTime('NOW');
                $this->emailService->sendLog($name, $type, $message, $time);
            }
        } else {
            echo 'ya hay resultados';
        }

    }
}