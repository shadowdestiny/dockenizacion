<?php


namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\shared\config\interfaces\ISession;
use Phalcon\DiInterface;

class DomainAdminServiceFactory
{

    private $entityManager;

    public function __construct(DiInterface $di)
    {
        $this->entityManager = $di->get('entityManager');
    }

    public function getAuthUserService(ISession $session = null)
    {
        if(!$session) $session = new PhalconSessionWrapper();
        return new AuthUserService($session);
    }

    public function getMaintenanceUserService()
    {
        return new MaintenanceUserService($this->entityManager);
    }

    public function getMaintenanceDrawService()
    {
        return new MaintenanceDrawService($this->entityManager);
    }

}