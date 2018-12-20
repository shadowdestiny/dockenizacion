<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 4/09/18
 * Time: 13:24
 */

namespace EuroMillions\web\components\logger\Adapter;

use LegalThings\CloudWatchLogger;

class CloudWatch extends \Phalcon\Logger\Adapter implements \Phalcon\Logger\AdapterInterface
{


    protected $cloudWatch;

    public function __construct(CloudWatchLogger $cloudWatch)
    {
        $this->cloudWatch = $cloudWatch;
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
        $this->cloudWatch->log($message);
    }

    public function log($type, $message = null, array $context = null)
    {
        if(getenv('EM_ENV') !== 'test' or getenv('EM_ENV') !== 'development')
        {
            $arr = explode(":", $message);
            $this->cloudWatch->log([
                $arr[0] => $arr[1]
            ]);
        }
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