<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;

use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\vo\EuroMillionsResult;
use EuroMillions\vo\ServiceActionResult;

class PlayService
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\entities\PlayConfig');
    }

    /**
     * @param User $user
     * @param EuroMillionsResult $euromillionsResult
     * @return ServiceActionResult
     */
    public function play(User $user, EuroMillionsResult $euromillionsResult)
    {
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euromillionsResult
            ]
        );
        $this->playConfigRepository->add($playConfig);
        return new ServiceActionResult(true);
    }

}