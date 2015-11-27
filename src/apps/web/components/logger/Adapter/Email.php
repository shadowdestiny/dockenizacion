<?php


namespace EuroMillions\web\components\Logger\Adapter;


use EuroMillions\web\services\EmailService;

class Email extends \Phalcon\Logger\Adapter implements \Phalcon\Logger\AdapterInterface
{

    protected $name;

    protected $emailService;

    public function __construct($name, EmailService $emailService)
    {
        $this->name = $name;
        $this->emailService = $emailService;
    }

    /**
     * Returns the internal formatter
     *
     * @return \Phalcon\Logger\FormatterInterface
     */
    public function getFormatter()
    {
        // TODO: Implement getFormatter() method.
    }

    /**
     * Writes the log to the file itself
     *
     * @param string  $message
     * @param integer $type
     * @param integer $time
     * @param array   $context
     */
    public function logInternal($message, $type, $time, $context = array())
    {
        $this->emailService->sendLog($this->name, $type, $message, $time);
    }

    /**
     * Closes the logger
     *
     * @return bool
     */
    public function close()
    {
        $this->close();
    }
}