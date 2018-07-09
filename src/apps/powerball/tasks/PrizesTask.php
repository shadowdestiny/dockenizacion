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


class PrizesTask extends TaskBase
{

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
        while(true)
        {
            $result = $this->serviceFactory->getCloudService()->cloud()->queue()->receiveMessage();

            if(count($result->get('Messages')) > 0)
            {
                foreach($result->get('Messages') as $message)
                {
                    $body = $message['Body'];
                    $this->prizeService->calculatePrizeAndInsertMessagesInQueue('2018-07-07', 'PowerBall');
                }
            }




//            if($message['Messages'] !== null)
//            {
//                $resultMessage = array_pop($message['Messages']);
//                $queue_handle = $resultMessage['ReceiptHandle'];
//                $message = $resultMessage['Body'];
//
//                print_r($message);
//
//                //$this->prizeService->calculatePrizeAndReturnMessage('2018-07-04');
//            }
        }



    }


}