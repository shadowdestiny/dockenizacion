<?php


namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use Phalcon\DiInterface;

class DomainAdminServiceFactory
{

    private $entityManager;

    public function __construct(DiInterface $di)
    {
        $this->entityManager = $di->get('entityManager');
    }

    public function getAuthUserService(EntityManager $entityManager = null)
    {
        if(!$entityManager) $entityManager = $this->entityManager;
        return new AuthUserService();
    }

    public function getMaintenanceService()
    {
        return new MaintenanceUserService($this->entityManager);
    }

    public function getMaintenanceDrawService()
    {
        return new MaintenanceDrawService($this->entityManager);
    }

}