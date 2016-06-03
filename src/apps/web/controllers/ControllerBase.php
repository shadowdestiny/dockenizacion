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
}

