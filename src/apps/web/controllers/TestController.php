<?php
namespace EuroMillions\web\controllers;

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

    public function reactAction()
    {
        $this->noRender();
        echo "
        <html>
        <head></head>
        <body>
        <div id='example'>Example</div>
        <script src='/w/js/react/play.js'></script>
        </body>
        </html>
        ";
    }

    public function httpsAction()
    {
        $this->noRender();
        $request = new \Phalcon\Http\Request();
        var_dump($request->getScheme());
    }

    public function urlAction()
    {
        $this->noRender();
        var_dump($this->router->getRewriteUri());
    }

}

