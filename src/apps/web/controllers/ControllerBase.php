<?php
namespace EuroMillions\web\controllers;

use EuroMillions\shared\dto\RestrictedAccessConfig;
use EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByIpAndHttpAuth;
use EuroMillions\shared\components\RestrictedAccess;
use EuroMillions\shared\vo\HttpUser;
use EuroMillions\web\components\MaxMindWrapper;
use EuroMillions\web\components\tags\MetaDescriptionTag;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\factories\DomainServiceFactory;
use Phalcon\Http\Response\CookiesInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;


/**
 * @property CookiesInterface $cookies
 */
class ControllerBase extends Controller
{
    /** @var  DomainServiceFactory */
    protected $domainServiceFactory;

    protected $metaTag;

    public function initialize()
    {
        $this->domainServiceFactory = $this->di->get('domainServiceFactory');
        $this->tag->setTitle(' | EuroMillions.com');
        $this->redirectFinalSlashUrl();
        $this->redirectSeoStrategy();
    }

    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    /**
     * @param AuthService $authService
     * @return User
     */
    protected function forceLogin(AuthService $authService)
    {
        if (!$authService->isLogged()) {
            $this->dispatcher->forward([
                'controller' => 'userAccess',
                'action'     => 'signIn',
                'params'     => [$this->dispatcher->getParams()],
            ]);
            return false;
        }
        return $authService->getCurrentUser();
    }

    /**
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        if (empty($this->cookies->has('EM-law')) && $dispatcher->getControllerName() != 'index') {
            $this->cookies->set('EM-law', 'accepted', time() + 15 * 86400);
        }
        $this->checkBannedCountry();
        $this->checkRestrictedAccess();
        $this->insertGoogleAnalyticsCodeViaEnvironment();
    }

    private function checkRestrictedAccess()
    {
        $config = $this->di->get('config');
        if ($config->restricted_access->activated !== '0') {
            $ra_config = new RestrictedAccessConfig([
                'allowedIps'      => $config->restricted_access->allowed_ips,
                'allowedHttpUser' => new HttpUser(
                    $config->restricted_access->user,
                    $config->restricted_access->pass
                )
            ]);
            $ra = new RestrictedAccess();
            if ($ra->isRestricted(new RestrictionByIpAndHttpAuth(), $this->request, $ra_config)) {
                exit();
            }
        }
    }

    protected function insertGoogleAnalyticsCodeViaEnvironment()
    {
        $environment = $this->di->get('environmentDetector');
        if( $environment->get() == 'production' ) {
            $this->view->setVar('ga_code', $environment->get());
        }
    }

    protected function checkBannedCountry()
    {
        $config = $this->di->get('config');
        $geoip = new MaxMindWrapper($config->geoip->database_files_path);
        if($geoip->isIpForbidden($this->request->getClientAddress())) {
            $this->view->pick('/landings/restricted');
        }
    }

    private function redirectFinalSlashUrl()
    {
        if ($this->request->getURI() != '/') {
            if (substr($this->request->getURI(), -1) == '/' && substr($this->request->getHttpHost(), 0, 4) == 'www.') {
                $this->response->redirect($this->request->getScheme() . "://" . substr($this->request->getHttpHost(), 4) . substr($this->request->getURI(), 0, -1), true, 301);
            } elseif (substr($this->request->getURI(), -1) == '/' && substr($this->request->getHttpHost(), 0, 4) != 'www.') {
                $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost() . substr($this->request->getURI(), 0, -1), true, 301);
            } elseif (substr($this->request->getURI(), -1) != '/' && substr($this->request->getHttpHost(), 0, 4) == 'www.') {
                $this->response->redirect($this->request->getScheme() . "://" . substr($this->request->getHttpHost(), 4) . $this->request->getURI(), true, 301);
            }
        }
    }

    private function redirectSeoStrategy()
    {
        if (substr($this->request->getHttpHost(), 0, 4) == 'play') {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost(), true, 301);
        }
        if (substr($this->request->getURI(), -1) == '!' || $this->request->getURI() == '/euromillions' || $this->request->getURI() == 'about-euromillions' || $this->request->getURI() == '/images/france_distribution.jpg' || $this->request->getURI() == '/scam-examples' || $this->request->getURI() == '/euromillions-prize-saves-last-ocean-paddle-steamer' || $this->request->getURI() == '/fr/euromillions-plus' || $this->request->getURI() == '/category/lottery-information') {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost(), true, 301);
        }
        if ($this->request->getURI() == '/euromillions-results' || $this->request->getURI() == '/euromillions/numbers' || $this->request->getURI() == '/euromillions-winners-public-appearance' || $this->request->getURI() == '/euromillions-jackpot-cap' || $this->request->getURI() == '/euromillions-tickets' || $this->request->getURI() == '/record-euromillions-jackpot-won-by-french-player') {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost() . "/euromillions/results", true, 301);
        }
        if ($this->request->getURI() == '/christmas/play') {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost() . "/christmas-lottery/play", true, 301);
        }
        if ($this->request->getURI() == '/euromillions-rules') {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost() . "/legal/index", true, 301);
        }
        if ($this->request->getURI() == '/faq-euromillions') {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost() . "/euromillions/faq", true, 301);
        }
        if (strpos($this->request->getURI(), '/lottery-news') !== false || strpos($this->request->getURI(), '/news-article') !== false || strpos($this->request->getURI(), '/newsarchive') !== false || strpos($this->request->getURI(), '/en/articles/') !== false) {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost(), true, 301);
        }
        if (strpos($this->request->getURI(), '/en/euromillions-results/') !== false) {
            $this->response->redirect($this->request->getScheme() . "://" . $this->request->getHttpHost() . "/euromillions/results/draw-history-page/", true, 301);
        }
    }
}

