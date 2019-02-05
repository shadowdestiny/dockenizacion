<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/06/18
 * Time: 11:26
 */


namespace EuroMillions\shared\tasks;


use EuroMillions\powerball\services\PrizesServices;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\LotteryService;
use EuroMillions\web\services\PrizeCheckoutService;
use EuroMillions\web\tasks\TaskBase;
use Money\Currency;
use Money\Money;


class PrizesTask extends TaskBase
{

    const BACKOFF_MAX = 7200;

    /** @var ServiceFactory  */
    protected $serviceFactory;

    /** @var PrizeCheckoutService */
    protected $prizeService;

    /** @var BetService */
    protected $betService;

    /** @var LotteryService */
    protected $lotteryService;

    /** @var DomainServiceFactory */
    protected $domainFactory;

    public function initialize(PrizeCheckoutService $prizeService = null, LotteryService $lotteryService = null)
    {
        parent::initialize();
        $this->serviceFactory = new ServiceFactory($this->getDI());
        $this->domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->prizeService = $prizeService ? $this->prizeService = $prizeService : $this->domainFactory->getPrizeCheckoutService();
        $this->lotteryService = $lotteryService ? $this->lotteryService = $lotteryService : $this->domainFactory->getLotteryService();
        $this->betService = $this->domainFactory->getBetService();
    }


    public function listenAction()
    {
        $resultConfigQueue = $this->di->get('config')['aws']['queue_results_endpoint'];
        $backOff = null;
        try {
            while(true && getenv( 'EM_ENV') !== 'test')
            {
                $this->createPrizes($resultConfigQueue,$backOff);
            }
            if(getenv('EM_ENV') == 'test')
            {
                $this->createPrizes($resultConfigQueue,$backOff);
            }
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

    }

    public function awardAction()
    {
        try
        {
            $backOff = null;
            $prizeConfigQueue = $this->di->get('config')['aws']['queue_prizes_endpoint'];
            while(true && getenv( 'EM_ENV') !== 'test')
            {
                $this->awardPrize($prizeConfigQueue,$backOff);
            }
            if(getenv('EM_ENV') == 'test')
            {
                $this->awardPrize($prizeConfigQueue,$backOff);
            }

        } catch(\Exception $e)
        {

            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $resultConfigQueue
     * @throws \Exception
     */
    private function createPrizes($resultConfigQueue,$backOff)
    {
        $result = $this->serviceFactory->getCloudService($resultConfigQueue)->cloud()->queue()->receiveMessage();
        if (count($result->get('Messages')) > 0) {
            $backOff = 0;
            foreach ($result->get('Messages') as $message) {
                $body = json_decode($message['Body'], true);
                if ($body['lotteryName'] != 'Error') {
                    $lottery = $this->lotteryService->getLotteryByName($body['lotteryName']);
                    $this->domainFactory->getPrizeCheckoutService($lottery)->calculatePrizeAndInsertMessagesInQueue($body['drawDate'], $lottery);
                }
            }
            $this->serviceFactory->getCloudService($resultConfigQueue)->cloud()->queue()->deleteMessage(
                $message['ReceiptHandle']
            );
        } else {
            unset($result);
            $backOff += 1;
            if ($backOff > self::BACKOFF_MAX) {
                $backOff = self::BACKOFF_MAX;
            }
            sleep($backOff);
        }
    }

    /**
     * @param $prizeConfigQueue
     * @throws \Money\UnknownCurrencyException
     */
    private function awardPrize($prizeConfigQueue,$backOff)
    {
        $result = $this->serviceFactory->getCloudService($prizeConfigQueue)->cloud()->queue()->receiveMessage();
        if (count($result->get('Messages')) > 0) {
            $backOff = 0;
            foreach ($result->get('Messages') as $message) {
                $body = json_decode($message['Body'], true);
                $amount = new Money((int)$body['prize'], new Currency('EUR'));
                $bet = $this->betService->getBet($body['betId']);
                $lottery = $bet->getPlayConfig()->getLottery();
                $this->domainFactory->getPrizeCheckoutService($lottery)->award($bet, $amount, [
                        'matches' => ['cnt' => $body['cnt'],
                                      'cnt_lucky' => $body['cnt_lucky']
                    ],
                    'userId' => $body['userId']
                ]);
            }
            $this->serviceFactory->getCloudService($prizeConfigQueue)->cloud()->queue()->deleteMessage(
                $message['ReceiptHandle']
            );
        } else {
            unset($result);
            $backOff += 1;
            if ($backOff > self::BACKOFF_MAX) {
                $backOff = self::BACKOFF_MAX;
            }
            sleep($backOff);
        }
    }


}