<?php
namespace EuroMillions\shared\config\bootstrap;

use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\shared\components\EnvironmentDetector;
use EuroMillions\shared\components\PhalconCookiesWrapper;
use EuroMillions\shared\components\PhalconRequestWrapper;
use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\shared\components\PhalconUrlWrapper;
use EuroMillions\shared\interfaces\IBootstrapStrategy;
use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use Phalcon;
use Phalcon\Di;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;


class WebBootstrapStrategy extends BootstrapStrategyBase implements IBootstrapStrategy
{
    protected $appPath;

    protected $assetsPath;

    public function __construct($appPath, $configPath, $assetsPath)
    {
        $this->appPath = $appPath;
        $this->assetsPath = $assetsPath;
        parent::__construct($configPath);

    }

    public function make()
    {
        $di = $this->dependencyInjector();
        $this->execute($di);
    }

    public function dependencyInjector()
    {
        $di = parent::dependencyInjector();
        $di->set('router', $this->configRouter(), true);
        $di->set('tag', $this->configTag(), true);
        $di->set('escaper', $this->configEscaper(), true);
        $di->set('security', $this->configSecurity(), true);
        $di->set('request', $this->configRequest(), false);
        $di->set('cookies', $this->configCookies($di->get('environmentDetector')), true);
        $di->set('session', $this->configSession(), true);
        $di->set('url', $this->configUrl(), true);
        $di->set('response', $this->configResponse(), true);
        return $di;
    }

    public function execute(Di $di)
    {
        (new Phalcon\Debug())->listen();
        $application = new Phalcon\Mvc\Application($di);
        $this->configureModules($application, $di);
        echo $application->handle()->getContent();
    }

    protected function configView($module)
    {
        $view = new Phalcon\Mvc\View();
        $compiled_path = $this->assetsPath . 'compiled_templates/';
        $view->setViewsDir($this->appPath . 'web/views/');
        if ($module === 'admin') {
            $view->setViewsDir($this->appPath . 'admin/views/');
        }
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
                $compiler->addFunction('currency_css', function ($currency) {
                    return '\EuroMillions\web\components\ViewHelper::getBodyCssForCurrency(' . $currency . ');';
                });
                return $volt;
            }
        ));
        return $view;
    }
    
    protected function configRouter()
    {
        $router = new Phalcon\Mvc\Router();

        $router->setDefaultModule('web');
        $router->add('/admin/:controller/:action/:params', array(
            'module'     => 'admin',
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ));

        $router->add('/admin/:controller(/?)', array(
            'module'     => 'admin',
            'controller' => 1,
            'action'     => 'index',
        ));

        $router->add('/admin(/?)', array(
            'module'     => 'admin',
            'controller' => 'index',
            'action'     => 'index',
        ));

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
        $router->add("/sign-up", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'signUp'
        ));
        $router->add("/logout", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'logout'
        ));
        $router->add("/validate", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'validate'
        ));
        $router->add("/passwordReset", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'passwordReset'
        ));
        $router->add("/forgotPassword", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'passwordReset'
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

    /**
     * @param Di $di
     * @return \EuroMillions\web\services\LanguageService
     */
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

    protected function configCookies(EnvironmentDetector $ed)
    {
        $wrapper = new PhalconCookiesWrapper($ed);
        $wrapper->useEncryption(true);
        return $wrapper;
    }


    protected function configUrl()
    {
        $url = new PhalconUrlWrapper();
        $url->setBaseUri('https://' . $_SERVER['HTTP_HOST']);
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

    protected function configDomainAdminServiceFactory(Di $di)
    {
        return new DomainAdminServiceFactory($di);
    }

    /**
     * @param $application
     */
    protected function configureModules(Phalcon\Mvc\Application $application)
    {
        $application->registerModules([
            'web'   => [
                'className' => 'EuroMillions\web\Module',
                'path'      => '../apps/web/Module.php',
            ],
            'admin' => [
                'className' => 'EuroMillions\admin\Module',
                'path'      => '../apps/admin/Module.php',
            ]
        ]);
        $di = $application->getDI();
        $eventsManager = new Phalcon\Events\Manager();
        $eventsManager->attach('application:beforeStartModule', function ($event, $application) use ($di) {
            $module_name = $event->getData();
            if ($module_name === 'web') {
                $web_module = $application->getModule($module_name);
                /** @var ModuleDefinitionInterface $object */
                $object = $di->get($web_module['className']);
                $di->set('language', $this->configLanguage($di), true);
                $di->set('view', $this->configView($module_name), true);
                $object->registerServices($di);
            }
            if ($module_name === 'admin') {
                $admin_module = $application->getModule($module_name);
                $di->set('view', $this->configView($module_name), true);
                $object = $di->get($admin_module['className']);
                $object->registerServices($di);
            }
        });
        $application->setEventsManager($eventsManager);    }

}