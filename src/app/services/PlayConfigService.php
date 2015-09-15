<?php


namespace EuroMillions\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\entities\User;
use EuroMillions\vo\EuroMillionsResult;
use EuroMillions\vo\ServiceActionResult;

class PlayConfigService
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param EuroMillionsResult $euromillionsResult
     * @return ServiceActionResult
     */
    public function create(User $user, EuroMillionsResult $euromillionsResult)
    {
        return new ServiceActionResult(true);
    }

}