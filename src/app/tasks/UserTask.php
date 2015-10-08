<?php


namespace EuroMillions\tasks;


use EuroMillions\services\DomainServiceFactory;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\services\PlayService;
use EuroMillions\services\ServiceFactory;

class UserTask extends TaskBase
{

    /** @var  LotteriesDataService */
    private $lotteriesDataService;

    /** @var  PlayService */
    private $playService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, PlayService $playService= null)
    {
        $domainFactory = new DomainServiceFactory($this->getDI(),new ServiceFactory($this->getDI()));
        ($lotteriesDataService) ? $this->lotteriesDataService = $lotteriesDataService : $this->lotteriesDataService = $domainFactory->getLotteriesDataService();
        ($playService) ? $this->playService = $playService : $this->playService = $domainFactory->getPlayService();
        parent::initialize();
    }

    public function checkResultAction(\DateTime $date)
    {
        if(!$date) {
            $date = new \DateTime();
        }



    }

}