<?php


namespace EuroMillions\web\components\transaction;


use EuroMillions\web\interfaces\ILogger;
use EuroMillions\web\services\notification_mediator\Colleague;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Formatter\Json;

class PhalconStreamJsonLogger extends Colleague implements ILogger
{


    /** @var Stream $logger */
    private $logger;

    public function __construct()
    {
        $this->logger = new Stream('php://stdout');
        $this->logger->setFormatter(new Json());
        $this->begin();
    }


    public function __destruct()
    {
        $this->commit();
    }


    public function begin()
    {
       // $this->logger->commit();
    }

    public function commit()
    {
       // $this->logger->commit();
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