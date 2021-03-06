<?php
namespace EuroMillions\web\services\factories;

use EuroMillions\shared\components\AwsCloud;
use EuroMillions\shared\components\AwsQueueImpl;
use EuroMillions\shared\services\AWSService;
use EuroMillions\shared\services\CloudService;
use EuroMillions\shared\services\FeatureFlagApiService;
use EuroMillions\web\components\PostMarkWrapper;
use EuroMillions\web\services\EmailService;
use EuroMillions\web\services\external_apis\FeatureFlagApi;
use EuroMillions\web\services\GeoService;
use EuroMillions\web\services\LoggerFactory;
use EuroMillions\web\services\LogService;
use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Http\Client\Provider\Curl;

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

    public function getCloudService($configQueue)
    {
        return new CloudService(
            new AwsCloud(
                new AwsQueueImpl($configQueue)
            )
        );

    }

    public function getFeatureFlagApiService()
    {
        return new FeatureFlagApiService(
            new FeatureFlagApi(
                new Curl()
            )
        );
    }

    public function getEmailService()
    {
        $config = $this->di->get('config')['mail'];
        $api_key = $config['api_key'];
        return new EmailService(
            new PostMarkWrapper($api_key),
            $this->di->get('config')['mail']
        );
    }



    public function getDI()
    {
        return $this->di;
    }
}