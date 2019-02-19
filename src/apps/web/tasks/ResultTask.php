<?php
namespace EuroMillions\web\tasks;


use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\factories\DomainServiceFactory;
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

    public function mainAction()
    {

    }

    public function updateAction(\DateTime $today = null)
    {
        //$results = $this->lotteryService->getLastDrawWithBreakDownByDate('EuroMillions', $this->lotteryService->getLastDrawDate('Euromillions'));
        $result = $this->lotteryService->getLastResult('EuroMillions');
        $breakdown = $this->lotteryService->getLastBreakdown('Euromillions');
        try {
            if (!$result['regular_numbers'][0]) {
                $this->lotteriesDataService->updateLastDrawResult('EuroMillions');
            }
            if (!$breakdown->getCategoryOne()->getName()) {
                $this->lotteriesDataService->updateLastBreakDown('EuroMillions');
            }
        } catch (\Exception $e) {
            $name = 'Breakdown is Empty';
            $type = '';
            $message = 'Breakdown is not saved correctly, is empty or have failed.';
            $time = $now = new \DateTime('NOW');
            $this->emailService->sendLog($name, $type, $message, $time);
        }
    }


    public function updatePowerballResultAction(\DateTime $now = null)
    {
        try {
            $resultConfigQueue = $this->di->get('config')['aws']['queue_results_endpoint'];
            $drawDate = $this->lotteryService->getLastDrawDate('PowerBall');
            $result = $this->lotteryService->getLastResult('PowerBall');
            $breakdown = $this->lotteryService->getLastBreakdown('PowerBall');
            if (!$result['regular_numbers'][0])
            {
                $this->lotteriesDataService->updateLastDrawResultLottery('PowerBall');
            }
            if(!$breakdown->getCategoryOne()->getName())
            {
                $this->lotteriesDataService->updateLastBreakDownLottery('PowerBall');
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

    /**
     * @param array $params
     */
    public function startAction(array $params)
    {
        if(!empty($params[1]))
        {
            $drawDate= new \DateTime($params[1]);
        }
        if(isset($params[0]) && in_array($params[0],['MegaMillions', 'PowerBall', 'EuroJackpot']))
        {
            try {
                if(empty($drawDate))
                {
                    $drawDate = $this->lotteryService->getLastDrawDate($params[0]);
                }
                $resultConfigQueue = $this->di->get('config')['aws']['queue_results_endpoint'];
                $this->domainServiceFactory->getServiceFactory()->getCloudService($resultConfigQueue)->cloud()->queue()->messageProducer([
                    'drawDate' => $drawDate->format('Y-m-d'),
                    'lotteryName' => $params[0]
                ]);
            } catch ( \Exception $e)
            {
                $this->domainServiceFactory->getServiceFactory()->getCloudService($resultConfigQueue)->cloud()->queue()->messageProducer([
                    'drawDate' => $drawDate->format('Y-m-d'),
                    'lotteryName' => 'Error'
                ]);
                throw new \Exception($e->getMessage());
            }
        }
        else{
            echo("Please, enter a valid param");
        }
    }

    public function importAllHistoricalDataFromPowerballAction()
    {
        try {
            $dependencies = [];
            $conversionService = $this->domainServiceFactory->getCurrencyConversionService();
            $dependencies['CurrencyConversionService'] = $conversionService;
            $results = $this->lotteryService->getAllResultFromLottery(new Curl(), Di::getDefault()->get('config')['lotto_api'], 'poweball');
            $this->lotteriesDataService->insertLotteryData($results->body,$dependencies, 'PowerBall');
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}