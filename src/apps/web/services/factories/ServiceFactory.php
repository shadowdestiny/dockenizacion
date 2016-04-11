<?php
namespace EuroMillions\web\services\factories;

use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\GeoService;
use EuroMillions\web\services\LoggerFactory;
use EuroMillions\web\services\LogService;
use Phalcon\DiInterface;
use EuroMillions\web\components\MandrillWrapper;

class ServiceFactory
{
    /** @var DiInterface */
    protected $di;

    public function __construct(DiInterface $di)
    {
        $this->di = $di;
    }

    public function getGeoService()
    {
        return new GeoService();
    }

    public function getLogService()
    {
        return new LogService(
            new LoggerFactory(
                $this->di->get('config')['log']['file_logs_path']
            )
        );
    }


    public function getEmailService()
    {
        $config = $this->di->get('config')['mail'];
        $api_key = $config['mandrill_api_key'];
        return new EmailService(
            new MandrillWrapper($api_key),
            $this->di->get('config')['mail']
        );
    }



    public function getDI()
    {
        return $this->di;
    }
}