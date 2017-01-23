<?php
namespace EuroMillions\admin\services;

use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\web\repositories\ReportsRepository;
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

    public function getMillonService()
    {
        return new MillonService($this->entityManager);
    }

    public function getReportsService()
    {
        return new ReportsService(new ReportsRepository($this->entityManager));
    }

    public function getTrackingService()
    {
        return new TrackingService($this->entityManager);
    }

}