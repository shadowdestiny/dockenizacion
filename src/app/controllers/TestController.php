<?php
namespace EuroMillions\controllers;

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
}

