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
            $message = $this->serviceFactory->getCloudService()->cloud()->queue()->receiveMessage();
            if($message !== null)
            {
                $this->prizeService->calculatePrizeAndReturnMessage('2018-07-04');

            }
        }



    }


}