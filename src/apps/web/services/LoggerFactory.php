<?php
namespace EuroMillions\web\services;

use EuroMillions\web\components\PhalconFileJsonLogger;
use EuroMillions\web\components\transaction\PhalconStreamJsonLogger;
use EuroMillions\web\interfaces\ILogger;
use EuroMillions\web\services\notification_mediator\Colleague;

class LoggerFactory extends Colleague
{
    const AUTHLOG = 'auth.log';

    const ACCOUNTLOG = 'account.log';

    private $fileLogsPath;

    public function __construct($fileLogsPath)
    {
        if(!empty($fileLogsPath))
        {
            $this->fileLogsPath = $fileLogsPath;
        }
    }
    /**
     * @param $logName
     * @return ILogger
     */
    public function get($logName)
    {
        return $this->$logName();
    }

    public function userAuth()
    {
        return new PhalconFileJsonLogger($this->fileLogsPath.self::AUTHLOG);
    }

    public function userAccount()
    {
        return new PhalconFileJsonLogger($this->fileLogsPath.self::ACCOUNTLOG);
    }

    public function paymentStream()
    {
        return new PhalconStreamJsonLogger();
    }

}