<?php
namespace EuroMillions\tasks;

use EuroMillions\services\DomainServiceFactory;
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