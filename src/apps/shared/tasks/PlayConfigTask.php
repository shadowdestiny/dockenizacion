<?php


namespace EuroMillions\shared\tasks;


use EuroMillions\web\services\BetService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\services\LotteryService;

class PlayConfigTask extends TaskBase
{


    /** @var  BetService $betService */
    protected $betService;


    public function initialize(BetService $betService = null, LotteryService $lotteryService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->betService = $betService ?: $this->betService = $domainFactory->getBetService();
        $this->lotteryService= $lotteryService ?: $this->lotteryService= $domainFactory->getLotteryService();
    }

    public function mainAction()
    {

    }

    public function updateLotteryAction($args = [0 =>'now'])
    {
        $today = new \DateTime($args[0]);
        if(!$today) {
            $today = new \DateTime();
        }
        if(isset($args[1]) && in_array($args[1],['MegaMillions', 'EuroMillions', 'PowerBall']))
        {
            $lottery=$this->lotteryService->getLotteryByName($args[1]);
            $this->betService->updatePlayConfigsToInactive($today, $lottery->getId());
        }
        else
        {
            echo("add lottery name ('MegaMillions', 'EuroMillions', 'PowerBall')");
        }
    }



}