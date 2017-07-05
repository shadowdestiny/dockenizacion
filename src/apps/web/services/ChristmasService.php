<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\repositories\ChristmasTicketsRepository;

class ChristmasService
{

    protected $entityManager;
    /** @var ChristmasTicketsRepository  */
    protected $christmasTicketsRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->christmasTicketsRepository = $entityManager->getRepository('EuroMillions\web\entities\ChristmasTickets');
    }

    public function getAvailableTickets()
    {
        return $this->christmasTicketsRepository->getAvailableTickets();
    }

}