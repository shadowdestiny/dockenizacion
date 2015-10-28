<?php
namespace EuroMillions\web\services;

use Phalcon\DiInterface;
use EuroMillions\web\interfaces\ICurrencyApi;
use EuroMillions\web\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\web\services\external_apis\YahooCurrencyApi;
use EuroMillions\web\components\MandrillWrapper;
use EuroMillions\web\interfaces\IMailServiceApi;

class ServiceFactory
{
    /** @var DiInterface */
    protected $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
    }

    public function getCurrencyService(ICurrencyApi $currencyApi = null)
    {
        if (!$currencyApi) $currencyApi = new YahooCurrencyApi(new RedisCurrencyApiCache($this->di->get('redisCache')));
        return new CurrencyService($currencyApi);
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


    public function getEmailService(IMailServiceApi $mailServiceApi = null, $config = null)
    {
        if (!$config) $config = $this->di->get('globalConfig')['mail'];
        $api_key = $config['mandrill_api_key'];
        if (!$mailServiceApi) $mailServiceApi = new MandrillWrapper($api_key);
        return new EmailService($mailServiceApi, $config);
    }


    public function getDI()
    {
        return $this->di;
    }
}