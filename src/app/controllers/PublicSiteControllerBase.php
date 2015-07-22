<?php
namespace EuroMillions\controllers;
use EuroMillions\entities\Language;
use EuroMillions\services\CurrencyService;
use EuroMillions\services\external_apis\LotteryApisFactory;
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

    public function initialize(LotteriesDataService $lotteriesDataService = null, LanguageService $languageService = null, CurrencyService $currencyService = null, UserService $userService = null)
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
        $this->languageService = $languageService ? $languageService : $this->language; //from DI
        $this->currencyService = $currencyService ? $currencyService : $this->domainServiceFactory->getCurrencyService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
    }

    public function afterExecuteRoute()
    {
        $this->setActiveLanguages();
        $this->setActiveCurrencies();
        $this->setCommonTemplateVariables();
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
        $jackpot = $this->userService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'));
        $this->view->setVar('jackpot_value', $jackpot->getAmount()/100);
        $this->view->setVar('currency_symbol', $this->currencyService->getSymbol($jackpot));
    }
}
