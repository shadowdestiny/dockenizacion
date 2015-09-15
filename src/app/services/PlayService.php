<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;

use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\vo\EuroMillionsLine;
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
     * @param EuroMillionsLine $euromillionsResult
     * @return ServiceActionResult
     */
    public function play(User $user, EuroMillionsLine $euromillionsResult)
    {
        if($user->getBalance()->getAmount() > 0){
            $playConfig = new PlayConfig();
            $playConfig->initialize([
                    'user' => $user,
                    'line' => $euromillionsResult
                ]
            );
            $this->playConfigRepository->add($playConfig);
            $this->entityManager->flush($playConfig);
            return new ServiceActionResult(true);
        } else {
            return new ServiceActionResult(false);
        }

    }

}