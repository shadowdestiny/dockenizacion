<?php
namespace EuroMillions\tasks;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\LotteriesDataService;
use Phalcon\CLI\Task;
use Phalcon\Di;

class JackpotTask extends Task
{
    /** @var  EntityManager */
    protected $entityManager;

    public function updateAction()
    {
        $service = new LotteriesDataService(Di::getDefault()->get('entityManager'), new LotteryApisFactory());
        $service->updateNextDrawJackpot('EuroMillions');
    }

    public function updatePreviousAction(\DateTime $today = null, $lotteriesDataService = null)
    {
        if (!$today) {
            $today = new \DateTime();
        }
        if (!$lotteriesDataService) {
            $lotteriesDataService = new LotteriesDataService(Di::getDefault()->get('entityManager'), new LotteryApisFactory());
        }
        /** @var \DateTime $date */
        $date = $lotteriesDataService->getLastDrawDate('EuroMillions', $today);
        $lotteriesDataService->updateNextDrawJackpot('EuroMillions', $date->sub(new \DateInterval('PT1M')));
    }
}