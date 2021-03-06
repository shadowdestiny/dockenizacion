<?php
namespace EuroMillions\eurojackpot\tasks;


use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\UserService;
use EuroMillions\web\tasks\TaskBase;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\NotificationValue;
use Phalcon\Di;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Provider\Exception;
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

    public function importAllHistoricalDataFromEuroJackpotAction()
    {
        try {
            $dependencies = [];
            $conversionService = $this->domainServiceFactory->getCurrencyConversionService();
            $dependencies['CurrencyConversionService'] = $conversionService;
            $results = $this->lotteryService->getAllResultFromLottery(new Curl(), Di::getDefault()->get('config')['eurojackpot_api'], 'eurojackpot');
            $this->lotteriesDataService->insertLotteryData($results->body,$dependencies, 'EuroJackpot');
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateEuroJackpotResultAction(\DateTime $now = null)
    {
        try {
            $resultConfigQueue = $this->di->get('config')['aws']['queue_results_endpoint'];
            $drawDate = $this->lotteryService->getLastDrawDate('EuroJackpot');
            $result = $this->lotteryService->getLastResult('EuroJackpot');
            $breakdown = $this->lotteryService->getLastBreakdown('EuroJackpot');
            if (!$result['regular_numbers'][0])
            {
                $this->lotteriesDataService->updateLastDrawResultLottery('EuroJackpot');
            }
            if(!empty($breakdown) && !$breakdown->getCategoryOne()->getName())
            {
                $this->lotteriesDataService->updateLastBreakDownLottery('EuroJackpot');
            }
        }catch (\Exception $e)
        {
            $this->domainServiceFactory->getServiceFactory()->getCloudService($resultConfigQueue)->cloud()->queue()->messageProducer([
                'drawDate' => $drawDate->format('Y-m-d'),
                'lotteryName' => 'Error'
            ]);
            throw new \Exception($e->getMessage());
        }
    }


}