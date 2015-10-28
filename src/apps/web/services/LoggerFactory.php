<?php
namespace EuroMillions\web\services;

use EuroMillions\web\components\PhalconFileJsonLogger;
use EuroMillions\web\interfaces\ILogger;

class LoggerFactory
{
    const AUTHLOG = 'auth.log';

    private $fileLogsPath;

    public function __construct($fileLogsPath)
    {
        $this->fileLogsPath = $fileLogsPath;
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
}