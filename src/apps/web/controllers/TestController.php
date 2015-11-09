<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\auth_strategies\WebAuthStorageStrategy;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\RememberToken;
use EuroMillions\web\vo\UserId;
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

