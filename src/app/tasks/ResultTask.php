<?php
namespace EuroMillions\tasks;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LotteriesDataService;
use Phalcon\CLI\Task;
use Phalcon\Di;

class ResultTask extends TaskBase
{
    public function updateAction()
    {
        $service = $this->domainServiceFactory->getLotteriesDataService();
        $service->updateLastDrawResult('EuroMillions');
    }
}