<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\interfaces\EmailTemplateDataStrategy;
use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\DomainServiceFactory;


abstract class EmailTemplateDecorator implements IEmailTemplate
{

    protected $emailTemplate;

    protected $config;

    protected $emailTemplateDataStrategy;

    /** @var DomainServiceFactory $domainServiceFactory*/
    protected $domainServiceFactory;

    public function __construct(IEmailTemplate $emailTemplate, EmailTemplateDataStrategy $emailTemplateDataStrategy)
    {
        $this->emailTemplate = $emailTemplate;
        $this->emailTemplateDataStrategy = $emailTemplateDataStrategy;
        $this->config = \Phalcon\Di::getDefault()->get('domainServiceFactory')->getServiceFactory()->getDI()->get('config');
    }

    abstract public function loadVars();

    abstract public function loadHeader();

    abstract public function loadFooter();

}