<?php
namespace EuroMillions\controllers;

use EuroMillions\components\NullPasswordHasher;
use EuroMillions\entities\User;
use EuroMillions\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\vo\Email;
use EuroMillions\vo\Password;
use EuroMillions\vo\RememberToken;
use EuroMillions\vo\UserId;
use Phalcon\Di;

class TestController extends PublicSiteControllerBase
{
    public function isLoggedAction()
    {
        $this->noRender();
        $as = $this->domainServiceFactory->getAuthService();
        var_dump($as->isLogged());
        var_dump($as->getCurrentUser());
    }

    public function sessionAction()
    {
        $this->noRender();
        $w = new WebAuthStorageStrategy($this->session, $this->cookies);
        $u = UserId::create();
        var_dump($u->id());
        $w->setCurrentUserId($u);
        $user = new User();
        $user->initialize([
            'id'=> $u,
            'password' => new Password('slkjD92df', new NullPasswordHasher()),
            'email' => new Email('a@a.com'),
            'rememberToken'=> new RememberToken('bldkla', 'skljdkf','kdlsfdkj')
        ]);
        $w->storeRemember($user);
        var_dump($this->cookies->get(WebAuthStorageStrategy::CURRENT_USER_VAR)->getValue());
        var_dump($this->cookies->get('hola')->getValue());
    }

}

