<?php
namespace EuroMillions\services;

use EuroMillions\components\PhalconFileJsonLogger;
use EuroMillions\interfaces\ILogger;

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