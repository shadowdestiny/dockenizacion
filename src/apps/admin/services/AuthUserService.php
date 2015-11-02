<?php


namespace EuroMillions\admin\services;


use EuroMillions\admin\vo\ActionResult;
use EuroMillions\shareconfig\interfaces\ISession;

class AuthUserService
{

    const CURRENT_ADMIN_USER_VAR = 'EM_ADMIN_current_user';

    protected $entityManager;

    protected $session;

    public function __construct(ISession $session)
    {
        $this->session = $session;
    }

    public function login($credentials)
    {
        if(is_array($credentials)) {
            //EMTD fetch credentials validation from database
            $user = $credentials['user'];
            $pass = $credentials['pass'];
            if($user == 'admin' && $pass == 'euromillions') {
                //EMTD improve session storage
                $this->session->set(self::CURRENT_ADMIN_USER_VAR, time());
                return new ActionResult(true);
            }else {
                return new ActionResult(false);
            }
        }
    }

    public function check_session()
    {
        if(!$this->session->get(self::CURRENT_ADMIN_USER_VAR)) {
            return new ActionResult(false);
        }else {
            return new ActionResult(true);
        }
    }
}