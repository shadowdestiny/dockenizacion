<?php
namespace EuroMillions\web\components;

use EuroMillions\web\interfaces\ILogger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Json;

class PhalconFileJsonLogger implements ILogger
{
    /** @var  FileAdapter */
    private $logger;

    public function __construct($logFile)
    {
        $this->logger = new FileAdapter($logFile);
        $this->logger->setFormatter(new Json());
        $this->begin();
    }

    public function __destruct()
    {
        $this->commit();
    }

    public function begin()
    {
        $this->logger->begin();
    }

    public function commit()
    {
        $this->logger->commit();
    }

    public function alert($message)
    {
        $this->logger->alert($message);
    }

    public function log($message, $level = null)
    {
        $this->logger->log($message, $level);
    }

    public function error($message)
    {
        $this->logger->error($message);
    }

    public function info($message)
    {
        $this->logger->info($message);
    }
}