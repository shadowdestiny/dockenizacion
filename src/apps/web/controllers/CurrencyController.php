<?php

namespace EuroMillions\web\controllers;
use EuroMillions\shared\controllers\PublicSiteControllerBase;

class CurrencyController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('currencies_name'));
        $this->view->setVar('pageController', 'currency');
    }
}
