<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\vo\dto\CurrencyDTO;

class CurrencyController extends PublicSiteControllerBase{

    public function indexAction()
    {
        $result = $this->domainServiceFactory->getCurrencyService()->getActiveCurrenciesCodeAndNames();
        $currencies_list = [];
        if($result->success()){
            foreach($result->getValues() as $currency) {
                $currencies_list[] = new CurrencyDTO($currency);
            }
        }
        $this->view->setVars(['currency_list' => $currencies_list ]);
    }
}