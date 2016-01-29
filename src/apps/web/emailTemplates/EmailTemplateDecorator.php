<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\LotteriesDataService;

abstract class EmailTemplateDecorator implements IEmailTemplate
{

    protected $emailTemplate;

    protected $config;

    protected $lotteriesDataService;

    /** @var DomainServiceFactory $domainServiceFactory*/
    protected $domainServiceFactory;

    public function __construct(IEmailTemplate $emailTemplate, LotteriesDataService $lotteriesDataService = null)
    {
        $this->emailTemplate = $emailTemplate;
        $this->domainServiceFactory = \Phalcon\Di::getDefault()->get('domainServiceFactory');
        $this->config = $this->domainServiceFactory->getServiceFactory()->getDI()->get('config');
        $this->lotteriesDataService = ($lotteriesDataService != null) ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
    }

    abstract public function loadVars();

    abstract public function loadHeader();

    abstract public function loadFooter();

}