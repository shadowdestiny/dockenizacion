<?php


namespace EuroMillions\web\emailTemplates;


use EuroMillions\web\interfaces\IEmailTemplateDataStrategy;
use EuroMillions\web\services\factories\DomainServiceFactory;
use Phalcon\Mvc\Url;


abstract class EmailTemplateDecorator implements IEmailTemplate
{

    protected $emailTemplate;

    protected $config;

    protected $emailTemplateDataStrategy;

    /** @var DomainServiceFactory $domainServiceFactory*/
    protected $domainServiceFactory;

    public function __construct(IEmailTemplate $emailTemplate, IEmailTemplateDataStrategy $emailTemplateDataStrategy)
    {
        $this->emailTemplate = $emailTemplate;
        $this->emailTemplateDataStrategy = $emailTemplateDataStrategy;
        /** @var Url $url */
        $url = \Phalcon\Di::getDefault()->get('domainServiceFactory')->getServiceFactory()->getDI()->get('url');
        $this->config = $url->getBaseUri();
    }

    abstract public function loadVars();

    abstract public function loadHeader();

    abstract public function loadFooter();

    public function loadVarsAsObject( array $vars = null)
    {
        if(!$vars) {
            $vars = $this->loadVars()['vars'];
        }
        $obj = new \stdClass();
        foreach($vars as $var) {
            $obj->{$var['name']} = $var['content'];
        }
        return $obj;
    }

}