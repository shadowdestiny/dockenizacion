<?php
namespace EuroMillions\admin\services;

use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\shared\services\CurrencyConversionService;
use EuroMillions\web\repositories\ReportsRepository;
use EuroMillions\web\services\BlogService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\external_apis\CurrencyConversion\CurrencyLayerApi;
use EuroMillions\web\services\external_apis\CurrencyConversion\RedisCurrencyApiCache;
use EuroMillions\web\services\GeoService;
use Phalcon\DiInterface;
use Phalcon\Http\Client\Provider\Curl;

class DomainAdminServiceFactory
{

    protected $entityManager;

    protected $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
        $this->entityManager = $this->di->get('entityManager');
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
        return new ReportsService(new ReportsRepository($this->entityManager), $this->entityManager, $this->getCurrencyConversionService());
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

    public function getBlogService()
    {
        return new BlogService($this->entityManager);
    }

    public function getCurrencyConversionService()
    {
        $redis_cache = $this->di->get('redisCache');
        return new CurrencyConversionService(
            new CurrencyLayerApi(
                '802187a0471a2c43f41b1ff3f777d26c',
                new Curl(),
                new RedisCurrencyApiCache($redis_cache)
            )
        );
    }
}