<?php
namespace EuroMillions\controllers;

use EuroMillions\entities\Language;
use EuroMillions\services\AuthService;
use EuroMillions\services\CurrencyService;
use EuroMillions\services\LanguageService;
use Doctrine\ORM\EntityManager;
use EuroMillions\services\LotteriesDataService;
use EuroMillions\services\UserService;
use Phalcon\Di;
use Phalcon\Mvc\View;

/**
 * Class PublicSiteControllerBase
 * @package EuroMillions\controllers
 * @property EntityManager $entityManager
 * @property LanguageService $language
 */
class PublicSiteControllerBase extends ControllerBase
{
    /** @var LotteriesDataService */
    protected $lotteriesDataService;
    /** @var  LanguageService */
    protected $languageService;
    /** @var  CurrencyService */
    protected $currencyService;
    /** @var  UserService */
    protected $userService;
    /** @var  AuthService */
    protected $authService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, LanguageService $languageService = null, CurrencyService $currencyService = null, UserService $userService = null, AuthService $authService= null)
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
        $this->languageService = $languageService ? $languageService : $this->language; //from DI
        $this->currencyService = $currencyService ? $currencyService : $this->domainServiceFactory->getCurrencyService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->authService = $authService ? $authService : $this->domainServiceFactory->getAuthService();
    }

    public function afterExecuteRoute()
    {
        $this->setActiveLanguages();
        $this->setActiveCurrencies();
        $this->setTopNavValues();
        $this->setNavValues();
        $this->setCommonTemplateVariables();
    }

    private function setTopNavValues()
    {
        $user_currency = $this->userService->getMyCurrencyNameAndSymbol();
        $this->view->setVar('user_currency', $user_currency);
    }

    private function setNavValues()
    {
        $this->view->setVar('user_logged', $this->authService->isLogged());
    }

    private function setActiveCurrencies()
    {
        $currencies = $this->currencyService->getActiveCurrenciesCodeAndNames();
        $this->view->setVars(['currencies' => $currencies]);
    }

    private function setActiveLanguages()
    {
        $languages = $this->languageService->activeLanguages();
        $view_params = [];
        /** @var Language $language */
        foreach ($languages as $language) {
            $view_params[] = $language->toValueObject();
        }
        $this->view->setVars(['languages' => $view_params]);
    }

    private function setCommonTemplateVariables()
    {
        $this->view->setVar('currency_symbol_first', true);
    }
}
