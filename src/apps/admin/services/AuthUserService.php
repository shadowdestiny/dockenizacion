<?php


namespace EuroMillions\admin\services;


use EuroMillions\admin\vo\ActionResult;

class AuthUserService
{

    protected $entityManager;

    public function __construct()
    {

    }

    public function login($credentials)
    {
        if(is_array($credentials)) {
            //EMTD fetch credentials validation from database
            $user = $credentials['user'];
            $pass = $credentials['pass'];
            if($user == 'admin' && $pass == 'euromillions') {
                return new ActionResult(true);
            }else {
                return new ActionResult(false);
            }
        }
    }
}