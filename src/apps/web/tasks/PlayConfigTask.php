<?php


namespace EuroMillions\web\tasks;


use EuroMillions\web\services\BetService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;

class PlayConfigTask extends TaskBase
{


    /** @var  BetService $betService */
    protected $betService;


    public function initialize(BetService $betService = null)
    {
        parent::initialize();
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->betService = $betService ?: $this->betService = $domainFactory->getBetService();
    }

    public function mainAction()
    {

    }

    public function updateAction($args = 'now')
    {
        $today = new \DateTime($args[0]);
        if(!$today) {
            $today = new \DateTime();
        }
        $this->betService->updatePlayConfigsToInactive($today);
    }



}