<?php
namespace EuroMillions\web\services\factories;

use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\GeoService;
use EuroMillions\web\services\LoggerFactory;
use EuroMillions\web\services\LogService;
use Phalcon\DiInterface;
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

    /**
     * @return GeoService
     */
    public function getGeoService()
    {
        return new GeoService();
    }

    public function getLogService(LoggerFactory $loggerFactory = null)
    {
        $loggerFactory = $loggerFactory ?: new LoggerFactory($this->di->get('globalConfig')['log']['file_logs_path']);
        return new LogService($loggerFactory);
    }


    public function getEmailService(IMailServiceApi $mailServiceApi = null, $config = null)
    {
        $config = $config ?: $this->di->get('globalConfig')['mail'];
        $api_key = $config['mandrill_api_key'];
        $mailServiceApi = $mailServiceApi ?: new MandrillWrapper($api_key);
        return new EmailService($mailServiceApi, $config);
    }



    public function getDI()
    {
        return $this->di;
    }
}