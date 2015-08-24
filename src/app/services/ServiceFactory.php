<?php
namespace EuroMillions\services;

use Phalcon\DiInterface;
use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\services\external_apis\YahooCurrencyApi;

class ServiceFactory
{
    /** @var DiInterface */
    protected $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
    }

    public function getCurrencyService(ICurrencyApi $currencyApi = null, LanguageService $languageService = null)
    {
        if (!$currencyApi) $currencyApi = new YahooCurrencyApi(new RedisCurrencyApiCache($this->di->get('redisCache')));
        if (!$languageService) $languageService = $this->di->get('language');
        return new CurrencyService($currencyApi, $languageService);
    }

    /**
     * @return GeoService
     */
    public function getGeoService()
    {
        return new GeoService();
    }

    public function getLogService(LoggerFactory $loggerFactory = null)
    {
        if (!$loggerFactory) $loggerFactory = new LoggerFactory($this->di->get('globalConfig')['log']['file_logs_path']);
        return new LogService($loggerFactory);
    }
}