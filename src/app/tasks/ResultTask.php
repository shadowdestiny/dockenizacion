<?php
namespace EuroMillions\tasks;
use EuroMillions\entities\Language;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\LotteriesDataService;
use Phalcon\CLI\Task;

class ResultTask extends Task
{
    /** @var  EntityManager */
    protected $entityManager;

    public function updateAction()
    {
        $service = new LotteriesDataService();
        $service->updateLastDrawResult('EuroMillions');
    }
}