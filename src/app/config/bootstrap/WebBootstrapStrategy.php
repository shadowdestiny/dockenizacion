<?php
namespace EuroMillions\config\bootstrap;

use DebugBar\Bridge\DoctrineCollector;
use Doctrine\DBAL\Logging\DebugStack;
use EuroMillions\components\EnvironmentDetector;
use EuroMillions\components\PhalconCookiesWrapper;
use EuroMillions\components\PhalconRequestWrapper;
use EuroMillions\components\PhalconSessionWrapper;
use EuroMillions\components\PhalconUrlWrapper;
use EuroMillions\interfaces\IBootstrapStrategy;
use EuroMillions\services\DomainServiceFactory;
use Phalcon;
use Phalcon\Di;
use Phalcon\Events\Event;
use Snowair\Debugbar\ServiceProvider;

class WebBootstrapStrategy extends BootstrapStrategyBase implements IBootstrapStrategy
{
    protected $appPath;

    protected $assetsPath;

    const CONFIG_FILENAME = 'web_config.ini';

    public function __construct($appPath, $globalConfigPath, $configPath, $assetsPath)
    {

        $this->appPath = $appPath;
        $this->assetsPath = $assetsPath;
        parent::__construct($globalConfigPath, $configPath);

    }

    public function make()
    {
        $di = $this->dependencyInjector();
        $this->execute($di);
    }

    public function dependencyInjector()
    {
        $di = parent::dependencyInjector();
        $di->set('view', $this->configView(), true);
        $di->set('request', $this->configRequest(), false);
        $di->set('url', $this->configUrl($di), true);
        $di->set('dispatcher', $this->configDispatcher(), true);
        $di->set('router', $this->configRouter(), true);
        $di->set('response', $this->configResponse(), true);
        $di->set('cookies', $this->configCookies(), true);
        $di->set('session', $this->configSession(), true);
        $di->set('tag', $this->configTag(), true);
        $di->set('escaper', $this->configEscaper(), true);
        $di->set('security', $this->configSecurity(), true);
        $di->set('language', $this->configLanguage($di), true);
        return $di;
    }

    public function execute(Di $di)
    {
        (new Phalcon\Debug())->listen();
        $application = new Phalcon\Mvc\Application($di);
        // CONFIGURE DEBUGBAR
        $di['app'] = $application;
        (new ServiceProvider(APP_PATH . 'config/debugbar.php'))->start();
        $em = $di->get('entityManager');
        $debugStack = new DebugStack();
        $em->getConnection()->getConfiguration()->setSQLLogger($debugStack);
        $debugbar = $di->get('debugbar');
        $debugbar->addCollector(new DoctrineCollector($debugStack));
        echo $application->handle()->getContent();
    }

    protected function configLanguage(Di $di)
    {
        /** @var DomainServiceFactory $dsf */
        $dsf = $di->get('domainServiceFactory');
        return $dsf->getLanguageService();
    }

    protected function configView()
    {
        $compiled_path = $this->assetsPath . 'compiled_templates/';
        $view = new Phalcon\Mvc\View();
        $view->setViewsDir($this->appPath . 'views/');
        $view->registerEngines(array(
            ".volt" => function ($view, $di) use ($compiled_path) {
                $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
                $volt->setOptions(array(
                    "compiledPath"      => $compiled_path,
                    "compiledExtension" => ".compiled",
                    "compileAlways"     => true, //EMDEPLOY en producción debería ser false y stat
                ));
                $compiler = $volt->getCompiler();
                $compiler->addFilter('number_format', 'number_format');
                return $volt;
            }
        ));
        return $view;
    }

    protected function configRequest()
    {
        return new PhalconRequestWrapper();
    }

    protected function configUrl(Di $di)
    {
        $request = $di->get('request');
        $url = new PhalconUrlWrapper();
        $url->setBaseUri($request->getScheme() . '://localhost:8080/');
        $url->setStaticBaseUri($request->getScheme() . '://localhost:8080/'); //EMTD pasar por configuración
        return $url;
    }

    protected function configDispatcher()
    {
        $eventsManager = new Phalcon\Events\Manager();
        $eventsManager->attach("dispatch", function (Event $event, Phalcon\Mvc\Dispatcher $dispatcher, \Exception $exception = null) {

            //The controller exists but the action not
            if ($event->getType() == 'beforeNotFoundAction') {
                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action'     => 'notfound'
                ));
                return false;
            }
            if ($event->getType() == 'beforeException') {
                switch ($exception->getCode()) {
                    case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(array(
                            'controller' => 'index',
                            'action'     => 'notfound',
                            'params'     => array($exception)
                        ));
                        return false;
                }
            }
            return true;
        });

        $dispatcher = new Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $dispatcher->setDefaultNamespace('EuroMillions\controllers');

        return $dispatcher;
    }

    protected function configRouter()
    {
        $router = new Phalcon\Mvc\Router();
        $router->notFound(array(
            "controller" => "index",
            "action"     => "notfound"
        ));
        $router->add("/", array(
            'controller' => 'index',
            'action'     => 'index'
        ));
        $router->add("/sign-in", array(
            'controller' => 'user-access',
            'action'     => 'signIn'
        ));
        $router->add("/userAccess/:action", array(
            'controller' => 'user-access',
            'action' => 1
        ));
        $router->add('/ajax/:controller/:action/:params', array(
            'namespace'  => 'EuroMillions\controllers\ajax',
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ));
        $router->setDefaults(array(
            'controller' => 'index',
            'action'     => 'index'
        ));
        $router->removeExtraSlashes(true);
        return $router;
    }

    protected function configResponse()
    {
        return new Phalcon\Http\Response();
    }

    protected function configCookies()
    {
        $wrapper = new PhalconCookiesWrapper();
        $wrapper->useEncryption(true);
        return $wrapper;
    }

    protected function configSession()
    {
        $session = new PhalconSessionWrapper();
        $session->start();
        return $session;
    }

    protected function configTag()
    {
        return new Phalcon\Tag();
    }

    protected function configEscaper()
    {
        return new Phalcon\Escaper();
    }

    protected function configSecurity()
    {
        return new Phalcon\Security();
    }

    protected function getConfigFileName(EnvironmentDetector $em)
    {
        return self::CONFIG_FILENAME;
    }
}