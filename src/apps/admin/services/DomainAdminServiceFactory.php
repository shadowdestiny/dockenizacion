<?php
namespace EuroMillions\admin\services;

use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use Phalcon\DiInterface;

class DomainAdminServiceFactory
{

    private $entityManager;

    public function __construct(DiInterface $di)
    {
        $this->entityManager = $di->get('entityManager');
    }

    public function getAuthUserService()
    {
        return new AuthUserService(new PhalconSessionWrapper());
    }

    public function getMaintenanceUserService()
    {
        return new MaintenanceUserService($this->entityManager);
    }

    public function getMaintenanceDrawService()
    {
        return new MaintenanceDrawService($this->entityManager);
    }

    public function getMaintenanceWithdrawService()
    {
        return new MaintenanceWithdrawService($this->entityManager);
    }


}