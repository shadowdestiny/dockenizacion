<?php
/**
 * Created by PhpStorm.
 * User: wilcar
 * Date: 28/03/19
 * Time: 04:31 PM
 */

namespace EuroMillions\shared\config\bootstrap\modules;

use Phalcon\Di;

abstract class Module
{
    protected $name;

    protected $application;

    protected $di;

    protected $appPath;

    protected $assetsPath;

    protected $diView;

    public function __construct($name, $application, $di, $appPath, $assetsPath, $diView)
    {
        $this->name = $name;
        $this->application = $application;
        $this->di = $di;
        $this->appPath = $appPath;
        $this->assetsPath = $assetsPath;
        $this->diView = $diView;
    }

    public function configure()
    {
        $web_module = $this->application->getModule($this->name);
        /** @var ModuleDefinitionInterface $object */
        $object = $this->di->get($web_module['className']);
        $this->di->set('language', $this->configLanguage($this->di), true);
        $this->di->set('view', $this->configView(), true);
        $object->registerServices($this->di);
    }

    protected abstract function getViewDir();

    protected function configLanguage(Di $di)
    {
        /** @var DomainServiceFactory $dsf */
        $dsf = $di->get('domainServiceFactory');
        return $dsf->getLanguageService();
    }

    protected function configView()
    {
        $view = new \Phalcon\Mvc\View();
        $compiled_path = $this->assetsPath . 'compiled_templates/';
        $view->setViewsDir($this->appPath . $this->getViewDir());
        $view->setLayoutsDir('shared/views/');
        $view->registerEngines(array(
            ".volt" => function ($view, $di) use ($compiled_path) {
                $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                $volt->setOptions($this->voltConfigByEnvironment($compiled_path));
                $compiler = $volt->getCompiler();
                $compiler->addFilter('number_format', 'number_format');
                $compiler->addFunction('currency_css', function ($currency) {
                    return '\EuroMillions\web\components\ViewHelper::getBodyCssForCurrency(' . $currency . ');';
                });
                return $volt;
            }
        ));
        return $view;
    }

    protected function voltConfigByEnvironment($compiled_path)
    {
        $environment = $this->diView->get('environmentDetector');

        if ($environment->get() !== 'development' || $environment->get() !== 'test') {
            return [
                "compiledPath" => $compiled_path,
                "compiledExtension" => ".compiled",
                "stat" => true,
                "compileAlways" => true,
            ];
        }

        return [
            "compiledPath" => $compiled_path,
            "compiledExtension" => ".compiled",
            "stat" => true,
            "compileAlways" => true,
        ];
    }
}