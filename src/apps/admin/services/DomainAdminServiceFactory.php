<?php
namespace EuroMillions\admin\services;

use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\web\repositories\ReportsRepository;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\GeoService;
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
        return new AuthUserService(new PhalconSessionWrapper(), $this->entityManager);
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
        return new ReportsService(new ReportsRepository($this->entityManager), $this->entityManager);
    }

    public function getTrackingService()
    {
        return new TrackingService($this->entityManager);
    }

    public function getGeoService()
    {
        return new GeoService();
    }

    public function getCurrencyService()
    {
        return new CurrencyService($this->entityManager);
    }

    public function getTranslationService()
    {
        return new TranslationService($this->entityManager);
    }

    public function getUserAdminService()
    {
        return new UserAdminService($this->entityManager);
    }
}