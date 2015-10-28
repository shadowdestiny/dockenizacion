<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\services\DomainServiceFactory;
use Phalcon\Cli\Task;

class TaskBase extends Task
{
    /** @var  DomainServiceFactory */
    protected $domainServiceFactory;

    public function initialize()
    {
        $this->domainServiceFactory = $this->di->get('domainServiceFactory');
    }
}