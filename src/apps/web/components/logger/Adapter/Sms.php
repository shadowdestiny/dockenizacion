<?php


namespace EuroMillions\web\components\logger\Adapter;


use EuroMillions\web\interfaces\ISmsServiceApi;

class Sms extends \Phalcon\Logger\Adapter implements \Phalcon\Logger\AdapterInterface
{


    protected $name;

    protected $smsServiceApi;

    protected $numbers;

    public function __construct($name, ISmsServiceApi $smsServiceApi, array $numbers)
    {
        $this->name = $name;
        $this->smsServiceApi = $smsServiceApi;
        $this->numbers = $numbers;
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
        $message = '[ '.$time.' ]' . ' Error in ' . $this->name . ' ' . $message;
        $this->smsServiceApi->send($message, $this->numbers, true);
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
     * Closes the logger
     *
     * @return bool
     */
    public function close()
    {
        // TODO: Implement close() method.
    }
}