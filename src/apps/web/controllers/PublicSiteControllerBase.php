<?php
namespace EuroMillions\web\controllers;

use EuroMillions\shared\services\SiteConfigService;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\entities\Language;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\AuthService;
use EuroMillions\web\services\CartService;
use EuroMillions\web\services\CurrencyService;
use EuroMillions\web\services\LanguageService;
use Doctrine\ORM\EntityManager;
use EuroMillions\web\services\LotteriesDataService;
use EuroMillions\web\services\UserPreferencesService;
use EuroMillions\web\services\UserService;
use EuroMillions\web\vo\dto\CurrencyDTO;
use Money\Currency;
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
    /** @var  SiteConfigService $siteConfigService */
    protected $siteConfigService;

    /** @var  CartService $cartService */
    protected $cartService;


    public function initialize(LotteriesDataService $lotteriesDataService = null, LanguageService $languageService = null, CurrencyService $currencyService = null, UserService $userService = null, AuthService $authService= null, UserPreferencesService $userPreferencesService = null, SiteConfigService $siteConfigService = null, CartService $cartService = null )
    {
        parent::initialize();
        $this->lotteriesDataService = $lotteriesDataService ? $lotteriesDataService : $this->domainServiceFactory->getLotteriesDataService();
        $this->languageService = $languageService ? $languageService : $this->language; //from DI
        $this->currencyService = $currencyService ? $currencyService : $this->domainServiceFactory->getCurrencyService();
        $this->userService = $userService ? $userService : $this->domainServiceFactory->getUserService();
        $this->authService = $authService ? $authService : $this->domainServiceFactory->getAuthService();
        $this->userPreferencesService = $userPreferencesService ? $userPreferencesService : $this->domainServiceFactory->getUserPreferencesService();
        $this->siteConfigService = $siteConfigService ? $siteConfigService : new SiteConfigService($this->di->get('entityManager'), $this->currencyService);
        $this->cartService = $cartService ? $cartService : $this->domainServiceFactory->getCartService();
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
        $this->setVarWinningModal();

        $controller_not_referer = [
            'user-access',
            'currency',
            'user-settings',
            'account'
        ];

        if(!in_array($dispatcher->getControllerName(),$controller_not_referer, false)) {
            $this->session->set('original_referer','/'.$dispatcher->getControllerName().'/'.$dispatcher->getActionName());
        }
    }

    public function checkAuth()
    {
        $this->authService->tryLoginWithRemember();
    }

    protected function setTopNavValues()
    {
        $user_currency = $this->userPreferencesService->getMyCurrencyNameAndSymbol();
        $current_currency = $this->userPreferencesService->getCurrency();
        $is_logged = $this->authService->isLogged();

        if($is_logged) {
            $user = $this->authService->getCurrentUser();
            $user = $this->userService->getUser($user->getId());
            $currency = $this->userPreferencesService->getCurrency();
            if($user->getUserCurrency()->getName() != $currency->getName() ) {
                $this->userPreferencesService->setCurrency($user->getUserCurrency());
                $user_currency = $this->userPreferencesService->getMyCurrencyNameAndSymbol();
            }
            $user_balance = $this->userService->getBalanceWithUserCurrencyConvert($this->authService->getCurrentUser()->getId(), $this->userPreferencesService->getCurrency());
            $user_balance_raw = $this->currencyService->convert($user->getBalance(),$this->userPreferencesService->getCurrency())->getAmount();
        }else{
            $user_balance = '';
            $user_balance_raw = '';
        }

        $this->view->setVar('current_currency', $current_currency->getName());
        $this->view->setVar('user_currency', $user_currency);
        $this->view->setVar('user_currency_code', $current_currency->getName());
        $this->view->setVar('user_balance', $user_balance);
        $this->view->setVar('user_balance_raw', $user_balance_raw);
        $this->view->setVar('jackpot', $this->userPreferencesService->getJackpotInMyCurrency($this->lotteriesDataService->getNextJackpot('EuroMillions'))->getAmount() / 100);
        $date_time_util = new DateTimeUtil();
        $date_next_draw = $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions');
        $this->view->setVar('countdown_next_draw', $date_time_util->getCountDownNextDraw($date_next_draw));

        //EMTD create a method helper to set this vars
        $single_bet_price = $this->lotteriesDataService->getSingleBetPriceByLottery('EuroMillions');
        $single_bet_price_currency = $this->currencyService->convert($single_bet_price, $current_currency);
        $bet_value = $this->currencyService->toString($single_bet_price_currency,$current_currency);
        $single_bet_price_currency_gbp = $this->currencyService->convert($single_bet_price, new Currency('GBP'));
        $bet_value_pound = $this->currencyService->toString($single_bet_price_currency_gbp, new Currency('GBP'));
        $this->view->setVar('bet_price', $bet_value);
        $this->view->setVar('bet_price_pound', $bet_value_pound);
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
       // $lottery_date_time = new \DateTime('2016-03-02 12:45:00');
        $time_to_remain = $dateUtil->getTimeRemainingToCloseDraw($lottery_date_time);

        if($time_to_remain) {
            $minutes_to_close = $dateUtil->restMinutesToCloseDraw($lottery_date_time);
        }
        $minutes_to_close_rounded = $dateUtil->restMinutesToCloseDraw($lottery_date_time,null,true);
        $last_minute = $dateUtil->isLastMinuteToDraw($lottery_date_time);
        $this->view->setVar('time_to_remain_draw', $time_to_remain);
        $this->view->setVar('minutes_to_close_rounded', (int) $minutes_to_close_rounded);
        $this->view->setVar('last_minute', $last_minute);
        $this->view->setVar('draw_date', date('Y/m/d H:i:s',$lottery_date_time->getTimestamp() - 1800));
        $this->view->setVar('timeout_to_closing_modal', 30 * 60 * 1000);
        $this->view->setVar('minutes_to_close', !empty($minutes_to_close) ? $minutes_to_close : '');
    }

    private function setVarWinningModal()
    {
        $is_logged = $this->authService->isLogged();
        if ($is_logged) {
            $user_id = $this->authService->getCurrentUser();
            /** @var User $user */
            $user = $this->userService->getUser($user_id->getId());
            if($user->getShowModalWinning()) {
                $this->view->setVar('show_modal_winning', true);
                $user->setShowModalWinning(false);
                $result = $this->userService->updateUser($user);
                if(!$result->success()) {
                }
            } else {
                $this->view->setVar('show_modal_winning', false);
            }
        } else {
            $this->view->setVar('show_modal_winning', false);
        }
    }

}
