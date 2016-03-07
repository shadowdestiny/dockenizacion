<?php
namespace EuroMillions\web\tasks;

use EuroMillions\web\services\factories\DomainServiceFactory;
use Phalcon\Cli\Task;

class TaskBase extends Task
{
    /** @var  DomainServiceFactory */
    protected $domainServiceFactory;

    protected $config;

    public function initialize()
    {
        $this->domainServiceFactory = $this->di->get('domainServiceFactory');
        $this->config = $this->getDI()->get('config');
    }
}