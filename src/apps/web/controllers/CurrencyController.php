<?php
namespace EuroMillions\web\controllers;


class CurrencyController extends PublicSiteControllerBase{

    public function indexAction()
    {
    	$this->tag->prependTitle('Currency Change');
    }
}
