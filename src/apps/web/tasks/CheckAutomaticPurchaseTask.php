<?php

namespace EuroMillions\web\tasks;

use EuroMillions\web\services\BetService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;

class CheckAutomaticPurchaseTask extends TaskBase
{

    /** @var  BetService $betService */
    protected $betService;

    public function initialize(BetService $betService = null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(), new ServiceFactory($this->getDI()));
        $this->betService = $betService ?: $this->betService = $domainFactory->getBetService();
        parent::initialize();

    }

    public function mainAction()
    {

    }

    public function verifyAction()
    {

    }
}