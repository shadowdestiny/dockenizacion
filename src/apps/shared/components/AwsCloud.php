<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 28/06/18
 * Time: 18:17
 */

namespace EuroMillions\shared\components;


use EuroMillions\shared\interfaces\ICloud;
use EuroMillions\shared\interfaces\IQueue;

class AwsCloud implements ICloud
{

    protected $queue;

    public function __construct(IQueue $queue)
    {
        $this->queue = $queue;
    }

    public function queue()
    {
        return $this->queue;
    }

}