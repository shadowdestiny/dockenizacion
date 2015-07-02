<?php
namespace EuroMillions\tasks;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LotteriesDataService;
use Phalcon\CLI\Task;
use Phalcon\Di;

class ResultTask extends Task
{
    /** @var  EntityManager */
    protected $entityManager;

    public function updateAction()
    {
        $service = new LotteriesDataService(Di::getDefault()->get('entityManager'), new LotteryApisFactory());
        $service->updateLastDrawResult('EuroMillions');
    }
}