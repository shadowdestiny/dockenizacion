<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\entities\Language;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\LanguageService;
use Doctrine\ORM\EntityManager;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\CurrencyDTO;
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
    /** @var  UserPreferencesService */
    protected $userPreferencesService;

    public function initialize(LotteriesDataService $lotteriesDataService = null, LanguageService $languageService = null, CurrencyService $currencyService = null, UserService $userService = null, AuthService $authService= null, UserPreferencesService $userPreferencesService = null)
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
        $this->languageService = $languageService ? $languageService : $this->language; //from DI
        $this->currencyService = $currencyService ? $currencyService : $this->domainServiceFactory->getCurrencyService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->authService = $authService ? $authService : $this->domainServiceFactory->getAuthService();
        $this->userPreferencesService = $userPreferencesService ? $userPreferencesService : $this->domainServiceFactory->getUserPreferencesService();
    }

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        $this->checkAuth();
        $this->setActiveLanguages();
        $this->setActiveCurrencies();
        $this->setTopNavValues();
        $this->setNavValues();
        $this->setCommonTemplateVariables();
        $this->setClosingModalVariables();

        $controller_not_referer = [
            'user-access',
            'currency',
            'user-settings'
        ];
        if(!in_array($dispatcher->getControllerName(),$controller_not_referer)) {
            $this->session->set('original_referer',$dispatcher->getControllerName().'/'.$dispatcher->getActionName());
        }
    }

    public function checkAuth()
    {
        $this->authService->tryLoginWithRemember();
    }

    private function setTopNavValues()
    {
        $user_currency = $this->userPreferencesService->getMyCurrencyNameAndSymbol();
        $current_currency = $this->userPreferencesService->getCurrency();
        $is_logged = $this->authService->isLogged();
        $user = $this->authService->getCurrentUser();
        if($is_logged){
            $user_balance = $this->userService->getBalanceWithUserCurrencyConvert($this->authService->getCurrentUser()->getId(), $this->userPreferencesService->getCurrency());
            $user_balance_raw = $this->currencyService->convert($user->getBalance(),$this->userPreferencesService->getCurrency())->getAmount();
        }else{
            $user_balance = '';
            $user_balance_raw = '';
        }
        $this->view->setVar('current_currency', $current_currency->getName());
        $this->view->setVar('user_currency', $user_currency);
        $this->view->setVar('user_balance', $user_balance);
        $this->view->setVar('user_balance_raw', $user_balance_raw);
    }

    private function setNavValues()
    {
        $is_logged = $this->authService->isLogged();
        if ($is_logged) {
            /** @var User $user */
            $user = $this->authService->getCurrentUser();
            $user_name = $user->getName();
        } else {
            $user_name = '';
        }

        $this->view->setVar('user_logged', $is_logged);
        $this->view->setVar('user_name', $user_name);

    }

    private function setActiveCurrencies()
    {
        $currencies = $this->currencyService->getCurrenciesMostImportant();
        $currencies_list = $this->currencyService->getActiveCurrenciesCodeAndNames();
        if($currencies->success()) {
            $currencies_dto = [];
            foreach($currencies->getValues() as $currency ) {
                $currencies_dto[] = new CurrencyDTO($currency);
            }           
            $this->view->setVars(['currencies' => $currencies_dto]);
            $currencies_dto = [];
            foreach($currencies_list->getValues() as $currency ) {
                $currencies_dto[] = new CurrencyDTO($currency);
            }
            $this->view->setVars(['currency_list' => $currencies_dto]);
        }
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

    private function setClosingModalVariables()
    {
        //Vars draw closing modal
        $dateUtil = new DateTimeUtil();
        $lottery_date_time = $this->domainServiceFactory->getLotteriesDataService()->getNextDateDrawByLottery('EuroMillions');
        $time_to_remain = $dateUtil->getTimeRemainingToCloseDraw($lottery_date_time);
        $this->view->setVar('time_to_remain_draw', (int) ($time_to_remain / 60) * 1000);
        $this->view->setVar('timeout_to_closing_modal', 30 * 60 * 1000);
        $this->view->setVar('interval_show_closing_modal',30000);
        $this->view->setVar('phrase_show_closing_modal', 'Today\’s draw is closed, you will play for the next');
    }

}
