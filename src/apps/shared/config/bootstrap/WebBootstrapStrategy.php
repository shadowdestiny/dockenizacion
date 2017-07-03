<?php
namespace EuroMillions\shared\config\bootstrap;

use EuroMillions\admin\services\DomainAdminServiceFactory;
use EuroMillions\shared\components\EnvironmentDetector;
use EuroMillions\shared\components\PhalconCookiesWrapper;
use EuroMillions\shared\components\PhalconRequestWrapper;
use EuroMillions\shared\components\PhalconSessionWrapper;
use EuroMillions\shared\components\PhalconUrlWrapper;
use EuroMillions\shared\interfaces\IBootstrapStrategy;
use EuroMillions\web\components\tags\EPayIframeTag;
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
    
    protected function configRouter()
    {
        $router = new Phalcon\Mvc\Router(false);
        $router->setDefaultModule('web');



        $router->add(
            "/:controller/:action",
            array(
                'module' => 'web',
                "controller" => 1,
                "action"     => 2
            )
        );

        $router->add("/test/markUserAsWinner/(.*)/([0-9])/([0-9])", array(
            "module"     => "web",
            'controller' => 'test',
            'action'     => 'markUserAsWinner',
            'userId'     => 1,
            'balls'     => 2,
            'stars'     => 3,
        ));

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

        $router->add('/admin/translation.html', array(
            'module'     => 'admin',
            'controller' => 'index',
            'action'     => 'translation',
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

        $router->add("/error/page404", array(
            "module"     => "web",
            'controller' => 'error',
            'action'     => 'page404',
        ));

        $router->add("/email-test", array(
            "module"     => "web",
            'controller' => 'email',
            'action'     => 'index',
        ));
        $router->add("/email-test/send", array(
            "module"     => "web",
            'controller' => 'email',
            'action'     => 'send',
        ));

        $router->add("/", array(
            "module"     => "web",
            'controller' => 'index',
            'action'     => 'index',
        ));

        $router->add("/{lottery:(euromillions)+}/play", array(
            "module"     => "web",
            "lottery"    => 1,
            'controller' => 'play',
            'action'     => 'index',
        ));

        $router->add("/christmas/play", array(
            "module"     => "web",
            "lottery"    => 'euromillions',
            'controller' => 'christmas',
            'action'     => 'index',
        ));

        $router->add("/profile/tickets/games", array(
            "module"     => "web",
            'namespace'  => 'EuroMillions\web\controllers\profile',
            'controller' => 'tickets',
            'action'     => 'games'
        ));

        $router->add("/profile/transactions", array(
            "module"     => "web",
            'namespace'  => 'EuroMillions\web\controllers\profile',
            'controller' => 'transactions',
            'action'     => 'transaction'
        ));

        $router->add("/withdraw", array(
            "module"     => "web",
            'namespace'  => 'EuroMillions\web\controllers\profile\payment',
            'controller' => 'withdraw',
            'action'     => 'withdraw'
        ));

        $router->add("/addFunds", array(
            "module"     => "web",
            'namespace'  => 'EuroMillions\web\controllers\profile\payment',
            'controller' => 'funds',
            'action'     => 'addFunds'
        ));


        $router->add("/account", array(
            "module"     => "web",
            'controller' => 'account',
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

        $router->add("/passwordReset/:params", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'passwordReset',
            'params'     => 1
        ));

        $router->add("/forgotPassword", array(
            "module"     => "web",
            'controller' => 'user-access',
            'action'     => 'forgotPassword'
        ));



        $router->add("/{lottery:(euromillions)+}/cart/profile", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'cart',
            'action'     => 'profile'
        ));

        $router->add("/{lottery:(euromillions)+}/order", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'order',
            'action'     => 'order'
        ));

        $router->add("/{lottery:(euromillions)+}/payment/payment(.*?)", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'payment',
            'action'     => 'payment',
        ));

        $router->add("/{lottery:(euromillions)+}/payment", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'payment',
            'action'     => 'payment',
        ));


        $router->add("/{lottery:(euromillions)+}/cart/login", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'cart',
            'action'     => 'login',
        ));


        $router->add("/{lottery:(euromillions)+}/empay/success", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'empay',
            'action'     => 'payment',
        ));

        $router->add("/{lottery:(euromillions)+}/gcp", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'gcp',
            'action'     => 'result',
        ));

        $router->add("/{lottery:(euromillions)+}/gcp/deposit", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'gcp',
            'action'     => 'deposit',
        ));


        $router->add("/{lottery:(euromillions)+}/empay/deposit", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'empay',
            'action'     => 'deposit',
        ));


        $router->add("/{lottery:(euromillions)+}/result/success", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'result',
            'action'     => 'success',
        ));

        $router->add("/{lottery:(euromillions)+}/result/failure", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'result',
            'action'     => 'failure',
        ));

        $router->add("/{lottery:(euromillions)+}/results/:action", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'numbers',
            'action'     => 2
        ));

        $router->add("/{lottery:(euromillions)+}/results/draw-history-page/:params", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'numbers',
            'action'     => 'pastResult',
            'params'      => 2
        ));

        $router->add("/{lottery:(euromillions)+}/article/discover_your_odds_of_winning_the_euromillions", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'article',
            'action'     => 'discover'
        ));
        $router->add("/{lottery:(euromillions)+}/article/euromillions_rules", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'article',
            'action'     => 'rules'
        ));
        $router->add("/{lottery:(euromillions)+}/article/euromillions_history", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'article',
            'action'     => 'history'
        ));
        $router->add("/{lottery:(euromillions)+}/article/euromillions_prize_structure", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'article',
            'action'     => 'prize'
        ));

        $router->add("/{lottery:(euromillions)+}/news", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'news',
            'action'     => 'index'
        ));

        $router->add("/{lottery:(euromillions)+}/es", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'news',
            'action'     => 'es'
        ));

        $router->add("/{lottery:(euromillions)+}/de", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'news',
            'action'     => 'de'
        ));




        $router->add("/{lottery:(euromillions)+}/results/draw-history-page", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'numbers',
            'action'     => 'pastList'
        ));
        $router->add("/{lottery:(euromillions)+}/help", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'help',
            'action'     => 'index'
        ));
        $router->add("/{lottery:(euromillions)+}/faq", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'faq',
            'action'     => 'index'
        ));

        $router->add("/{lottery:(euromillions)+}/results", array(
            "module"     => "web",
            'lottery'    => 1,
            'controller' => 'numbers',
            'action'     => 'index'
        ));

        $router->add('/ajax/:controller/:action/:params', array(
            "module"     => "web",
            'namespace'  => 'EuroMillions\web\controllers\ajax',
            'controller' => 1,
            'action'     => 2,
            'params'     => 3,
        ));

        $router->add("/currency", array (
            "module"     => "web",
            'controller' => 'currency',
            'action'     => 'index'
        ));

        $router->add("/sitemap", array(
            "module"     => "web",
            'controller' => 'sitemap',
            'action'     => 'index'
        ));

        $router->add("/contact", array(
            "module"     => "web",
            'controller' => 'contact',
            'action'     => 'index'
        ));

//        $router->setDefaults(array(
//            "module"     => "web",
//            'controller' => 'index',
//            'action'     => 'index'
//        ));
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
              //  $di->set('EPayIframe', function() { return new EPayIframeTag(); });
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


    protected function voltConfigByEnvironment($compiled_path)
    {
        $di = parent::dependencyInjector();
        $environment = $di->get('environmentDetector');
        if( $environment->get() !== 'development' || $environment->get() !== 'vagrant') {
            return  [
                "compiledPath"      => $compiled_path,
                "compiledExtension" => ".compiled",
                "stat" => true,
                "compileAlways"     => true,
            ];
        } else {
            return [
                "compiledPath"      => $compiled_path,
                "compiledExtension" => ".compiled",
                "stat" => true,
                "compileAlways"     => true,
            ];
        }

    }
}