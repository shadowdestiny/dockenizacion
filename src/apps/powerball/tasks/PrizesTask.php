<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 29/06/18
 * Time: 11:26
 */

namespace EuroMillions\powerball\tasks;


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

    const BACKOFF_MAX = 3600;

    /** @var ServiceFactory  */
    protected $serviceFactory;

    /** @var PrizeCheckoutService */
    protected $prizeService;

    public function initialize(PrizeCheckoutService $prizeService = null, LotteryService $lotteryService = null)
    {
        parent::initialize();
        $this->serviceFactory = new ServiceFactory($this->getDI());
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->prizeService = $prizeService ? $this->prizeService = $prizeService : $domainFactory->getPrizeCheckoutService();
    }


    public function listenAction()
    {
        $resultConfigQueue = $this->di->get('config')['aws']['queue_results_endpoint'];
        $backOff = 0;

        try {
            while(true)
            {
                $result = $this->serviceFactory->getCloudService($resultConfigQueue)->cloud()->queue()->receiveMessage();
                if(count($result->get('Messages')) > 0)
                {
                    $backOff = 0;
                    foreach($result->get('Messages') as $message)
                    {
                        $body = json_decode($message['Body'], true);
                        $this->prizeService->calculatePrizeAndInsertMessagesInQueue($body['drawDate'], $body['lotteryName']);
                    }
                    $this->serviceFactory->getCloudService($resultConfigQueue)->cloud()->queue()->deleteMessage(
                        $message['ReceiptHandle']
                    );
                } else {
                    $backOff += 0.5;
                    if ($backOff > self::BACKOFF_MAX) {
                        $backOff = self::BACKOFF_MAX;
                    }
                    sleep($backOff);
                }
            }
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

    }

    public function awardAction()
    {

    }


}