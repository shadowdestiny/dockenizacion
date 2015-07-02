<?php
namespace EuroMillions\config\bootstrap;

use EuroMillions\components\EmTranslationAdapter;
use EuroMillions\components\EnvironmentDetector;
use EuroMillions\interfaces\IBootstrapStrategy;
use EuroMillions\services\LanguageService;
use Phalcon;
use Phalcon\Di;
use Phalcon\Events\Event;

class WebBootstrapStrategy extends BootstrapStrategyBase implements IBootstrapStrategy
{
    protected $appPath;

    protected $assetsPath;

    protected $testsPath;

    const CONFIG_FILENAME = 'web_config.ini';

    public function __construct($appPath, $globalConfigPath, $configPath, $assetsPath, $testsPath)
    {

        $this->appPath = $appPath;
        $this->assetsPath = $assetsPath;
        $this->testsPath = $testsPath;
        parent::__construct($globalConfigPath, $configPath);

    }

    public function make()
    {
//        $this->generalConfig();
        $di = $this->dependencyInjector();
        $this->execute($di);
    }

    public function dependencyInjector()
    {
        $di = parent::dependencyInjector();
        $di->set('view', $this->configView(), true);
        $di->set('request', $this->configRequest(), false);
        $di->set('url', $this->configUrl(), true);
        $di->set('dispatcher', $this->configDispatcher(), true);
        $di->set('router', $this->configRouter(), true);
        $di->set('response',$this->configResponse(), true);
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
        echo $application->handle()->getContent();
    }

    protected function configLanguage(Di $di)
    {
        /** @var \Phalcon\Session\AdapterInterface $session */
        $session = $di->get('session');
        /** @var \Phalcon\Http\Request $request */
        $request = $di->get('request');
        if ($session->has("language")) {
            $language = $session->get("language");
        } else {
            $language = $request->getBestLanguage();
            $has_hyphen = strpos($language, '-');
            if ($has_hyphen !== false) {
                $language = substr($language, 0, $has_hyphen);
            }
        }
        $em = $di->get('entityManager');
        return new LanguageService($language, $em->getRepository('EuroMillions\entities\Language'), new EmTranslationAdapter($language, $em->getRepository('EuroMillions\entities\TranslationDetail')));
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
                var_dump($volt->getOptions());
                return $volt;
            }
        ));
        return $view;
    }

    protected function configRequest()
    {
        return new Phalcon\Http\Request();
    }

    protected function configUrl()
    {
        return new Phalcon\Mvc\Url();
    }

    protected function configDispatcher()
    {
        $eventsManager = new Phalcon\Events\Manager();
        $eventsManager->attach("dispatch", function(Event $event, Phalcon\Mvc\Dispatcher $dispatcher, \Exception $exception=null) {

            //The controller exists but the action not
            if ($event->getType() == 'beforeNotFoundAction') {
                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'fallBackToZend'
                ));
                return false;
            }
            if ($event->getType() == 'beforeException') {
                switch ($exception->getCode()) {
                    case Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(array(
                            'controller' => 'index',
                            'action' => 'fallBackToZend'
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
            "action" => "fallBackToZend"
        ));
        $router->add("/", array(
            'controller' => 'index',
            'action'     => 'index'
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
        return new Phalcon\Http\Response\Cookies();
    }

    protected function configSession()
    {
        $session = new Phalcon\Session\Adapter\Files();
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