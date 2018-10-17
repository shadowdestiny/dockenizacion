<?php


namespace EuroMillions\web\controllers;
use EuroMillions\shared\controllers\PublicSiteControllerBase;

class NewsController extends PublicSiteControllerBase
{

    public function indexAction()
    {
        $this->view->pick('landings/news');
    }

    public function esAction()
    {
        $this->view->pick('landings/es');
    }

    public function deAction()
    {
        $this->view->pick('landings/de');
    }

}