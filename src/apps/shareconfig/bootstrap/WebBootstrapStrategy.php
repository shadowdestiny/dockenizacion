<?php
namespace EuroMillions\shareconfig\bootstrap;

use EuroMillions\sharecomponents\EnvironmentDetector;
use EuroMillions\sharecomponents\PhalconCookiesWrapper;
use EuroMillions\sharecomponents\PhalconRequestWrapper;
use EuroMillions\sharecomponents\PhalconSessionWrapper;
use EuroMillions\sharecomponents\PhalconUrlWrapper;
use EuroMillions\shareconfig\interfaces\IBootstrapStrategy;
use EuroMillions\web\services\DomainServiceFactory;
use EuroMillions\web\services\ServiceFactory;
use Phalcon;
use Phalcon\Di;
use Phalcon\Events\Event;
use Phalcon\Mvc\ModuleDefinitionInterface;


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
//        $di->set('view', $this->configView(), true);
        $di->set('dispatcher', $this->configDispatcher(), true);
        $di->set('router', $this->configRouter(), true);
        $di->set('tag', $this->configTag(), true);
        $di->set('escaper', $this->configEscaper(), true);
        $di->set('security', $this->configSecurity(), true);
        $di->set('request', $this->configRequest(), false);
        $di->set('cookies', $this->configCookies(), true);
        $di->set('session', $this->configSession(), true);
        //$di->set('language', $this->configLanguage($di), true);
        $di->set('url', $this->configUrl($di), true);
        $di->set('response', $this->configResponse(), true);

        //$this->ownDependency($di);
        //$di->set('domainServiceFactory', $this->configDomainServiceFactory($di), true);

        return $di;
    }

    public function execute(Di $di)
    {
        $config = $di->get('config');
        ini_set('display_errors', $config->application['displayErrors']);
        ini_set('display_startup_errors', $config->application['displayStartupErrors']);
        error_reporting($config->application['errorReporting']);
        (new Phalcon\Debug())->listen();
        $application = new Phalcon\Mvc\Application($di);

        $application->registerModules([
            'web' => [
                'className' => 'EuroMillions\web\Module',
                'path' => '../apps/web/Module.php',
            ],
            'admin' => [
                'className' => 'EuroMillions\admin\Module',
                'path' => '../apps/admin/Module.php',
            ]
        ]);

        $this->ownDependency($application);
        // CONFIGURE DEBUGBAR
//        $di['app'] = $application;
//        (new ServiceProvider(APP_PATH . 'config/debugbar.php'))->start();

        echo $application->handle()->getContent();
    }

    protected function configView()
    {
        $compiled_path = $this->assetsPath . 'compiled_templates/';
        $view = new Phalcon\Mvc\View();
        $view->setViewsDir($this->appPath . 'web/views/');
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
        //$dispatcher->setDefaultNamespace('EuroMillions\controllers');

        return $dispatcher;
    }

    protected function configRouter()
    {
        $router = new Phalcon\Mvc\Router();

        $router->setDefaultModule('web');


        $router->add("/admin", [
            'module' => 'admin',
            'controller' => 'login',
            'action' => 'index'
        ]);

        $router->notFound(array(
            "module"     => "web",
            "controller" => "index",
            "action"     => "notfound"
        ));
        $router->add("/", array(
            "module"     => "web",
            'controller' => 'index',
            'action'     => 'index'
        ));
        $router->add("/sign-in", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'signIn'
        ));
        $router->add("/userAccess/:action", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action' => 1
        ));
        $router->add('/ajax/:controller/:action/:params', array(
            "module"     => "web",
            'namespace'  => 'EuroMillions\web\controllers\ajax',
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ));
        $router->setDefaults(array(
            "module"     => "web",
            'controller' => 'index',
            'action'     => 'index'
        ));
        $router->removeExtraSlashes(true);
        return $router;
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
        return $em->get().'_'.self::CONFIG_FILENAME;
    }

    protected function configLanguage(Di $di)
    {
        /** @var DomainServiceFactory $dsf */
        $dsf = $di->get('domainServiceFactory');
        return $dsf->getLanguageService();
    }

    protected function configSession()
    {
        $session = new PhalconSessionWrapper();
        $session->start();
        return $session;
    }

    protected function configRequest()
    {
        return new PhalconRequestWrapper();
    }

    protected function configCookies()
    {
        $wrapper = new PhalconCookiesWrapper();
        $wrapper->useEncryption(true);
        return $wrapper;
    }


    protected function configUrl(Di $di)
    {
        $request = $di->get('request');
        $url = new PhalconUrlWrapper();
        $url->setBaseUri($request->getScheme() . '://localhost:8080/');
        $url->setStaticBaseUri($request->getScheme() . '://localhost:8080/'); //EMTD pasar por configuración
        return $url;
    }

    protected function configResponse()
    {
        return new \Phalcon\Http\Response();
    }

    protected function configDomainServiceFactory(Di $di)
    {
       return new DomainServiceFactory($di, new ServiceFactory($di));
    }

    protected function ownDependency(Phalcon\Mvc\Application $application)
    {
        $di = $application->getDI();
        $eventsManager = new Phalcon\Events\Manager();
        $eventsManager->attach('application:beforeStartModule',function($event, $application) use($di){
            $module_name = $event->getData();
            if($module_name == 'web'){
                $web_module = $application->getModule($module_name);
                /** @var ModuleDefinitionInterface $object */
                $object = $di->get($web_module['className']);
                $di->set('domainServiceFactory', $this->configDomainServiceFactory($di), true);
                $di->set('language', $this->configLanguage($di), true);
                $di->set('view', $this->configView($di), true);
                $object->registerServices($di);
            }
        });
        $application->setEventsManager($eventsManager);
    }

}