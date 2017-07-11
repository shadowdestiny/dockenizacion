<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\ChristmasTickets;
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

    /**
     * @param $chistmasPost
     * @return array
     */
    public function getChristmasTicketsData($chistmasPost)
    {
        $christmasTicketsData = [];
        foreach ($chistmasPost as $key => $value) {
            $id = explode('_', $key)[1];
            if ($value != 0) {
                for ($i=0;$i<$value;$i++) {
                    $christmasTicketsData[] = $this->christmasTicketsRepository->findOneBy(['id' => $id]);
                }
            }
        }

        return $christmasTicketsData;
    }

}