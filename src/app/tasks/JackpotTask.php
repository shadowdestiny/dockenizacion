<?php
namespace EuroMillions\tasks;
use EuroMillions\entities\Language;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\LotteriesDataService;
use Phalcon\CLI\Task;

class JackpotTask extends Task
{
    /** @var  EntityManager */
    protected $entityManager;

    public function updateAction()
    {
        $service = new LotteriesDataService();
        $service->updateNextDrawJackpot('EuroMillions');
    }
}