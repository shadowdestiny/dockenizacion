<?php
namespace EuroMillions\web\controllers;

use EuroMillions\shared\dto\RestrictedAccessConfig;
use EuroMillions\shared\components\restrictedAccessStrategies\RestrictionByIpAndHttpAuth;
use EuroMillions\shared\components\RestrictedAccess;
use EuroMillions\shared\shareVO\HttpUser;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\DomainServiceFactory;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

class ControllerBase extends Controller
{
    /** @var  DomainServiceFactory */
    protected $domainServiceFactory;

    public function initialize()
    {
        $this->domainServiceFactory = $this->di->get('domainServiceFactory');
    }

    protected function noRender()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }

    /**
     * @param AuthService $authServicePlay
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

        $this->checkRestrictedAccess();
    }

    private function checkRestrictedAccess()
    {
        $config = $this->di->get('config');

        if (!empty($config->restricted_access)) {
            $ra_config = new RestrictedAccessConfig([
                'allowedIps'      => $config->restricted_access->allowed_ips,
                'allowedHttpUser' => new HttpUser(
                    $config->restricted_access->user,
                    $config->restricted_access->pass
                )
            ]);
            $ra = new RestrictedAccess();
            if ($ra->isRestricted(new RestrictionByIpAndHttpAuth(), $this->request, $ra_config)) {
                header('Location: http://localhost');
                exit();
            }
        }
    }
}