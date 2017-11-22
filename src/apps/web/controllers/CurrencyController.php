<?php

namespace EuroMillions\web\controllers;

class CurrencyController extends PublicSiteControllerBase
{
    public function indexAction()
    {
        $this->tag->prependTitle($this->languageService->translate('currencies_name'));
        $this->view->setVar('pageController', 'currency');
    }
}
