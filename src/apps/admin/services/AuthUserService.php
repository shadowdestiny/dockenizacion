<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\UserAdmin;
use EuroMillions\web\repositories\UserAdminRepository;
use Phalcon\Session\AdapterInterface;

class AuthUserService
{
    const CURRENT_ADMIN_USER_VAR = 'EM_ADMIN_current_user';

    protected $session;
    /** @var EntityManager $entityManager */
    protected $entityManager;
    /** @var UserAdminRepository $userAdminRepository */
    private $userAdminRepository;

    public function __construct(AdapterInterface $session, EntityManager $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->userAdminRepository = $this->entityManager->getRepository('EuroMillions\web\entities\UserAdmin');
    }

    public function login($credentials)
    {
        if (is_array($credentials)) {
            /** @var UserAdmin $userAdmin */
            $userAdmin = $this->userAdminRepository->findOneBy([
                'email' => $credentials['email'],
                'password' => $credentials['pass']
            ]);
            if (!empty($userAdmin)) {
                //EMTD improve session storage
                $this->session->set('userId', $userAdmin->getId());
                $this->session->set('userAccess', $userAdmin->getUseraccess());
                $this->session->set(self::CURRENT_ADMIN_USER_VAR, time());
                return new ActionResult(true);
            }
        }
        return new ActionResult(false);
    }

    public function check_session()
    {
        if (!$this->session->get(self::CURRENT_ADMIN_USER_VAR)) {
            return new ActionResult(false);
        } else {
            return new ActionResult(true);
        }
    }
}