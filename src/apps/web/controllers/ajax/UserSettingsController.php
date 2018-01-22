<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\entities\User;
use Money\Currency;

class UserSettingsController extends AjaxControllerBase
{
    public function setCurrencyAction($currency)
    {
        $this->loadCurrency($currency);
        echo json_encode(['result'=>'OK']);
    }

    public function setCurrencyReloadAction($currency)
    {
        $this->loadCurrency($currency);
        $this->response->redirect($this->session->get('original_referer'));
    }

    public function setLanguageAction($values)
    {
        $language = explode(',', $values)[0];
        $controller = explode(',', $values)[1];
        $this->loadLanguage($language);
        echo json_encode(['result'=>'OK', 'url' => $this->redirectToCurrentLanguage($language, $controller)]);
    }

    /**
     * @param $currency
     */
    private function loadCurrency($currency)
    {
        $user_service = $this->domainServiceFactory->getUserPreferencesService();
        $new_currency = new Currency($currency);
        $user_service->setCurrency($new_currency);
        $user = $this->domainServiceFactory->getAuthService()->getCurrentUser();
        if ($user instanceof User) {
            $this->domainServiceFactory->getUserService()->updateCurrency($user, $new_currency);
        }
    }

    /**
     * @param $language
     */
    private function loadLanguage($language)
    {
        $user_service = $this->domainServiceFactory->getUserPreferencesService();
        $user_service->setLanguage($language);
        $user = $this->domainServiceFactory->getAuthService()->getCurrentUser();
        if ($user instanceof User) {
            $this->domainServiceFactory->getUserService()->updateLanguage($user, $language);
        }
    }

    /**
     * @param $controller
     * @param $language
     *
     * @return mixed
     */
    private function redirectToCurrentLanguage($language, $controller)
    {
        if (empty($language) || empty($controller)) {
            return 0;
        } else {
            if (empty($this->getAllUrls()[$language][$controller])) {
                return 0;
            } else {
                return $this->getAllUrls()[$language][$controller];
            }
        }
    }

    /**
     * @return array
     */
    private function getAllUrls()
    {
        return [
            'en' => [
                'index' => '/',
                'christmasPlay' => '/christmas-lottery/play',
                'signin' => '/sign-in',
                'signup' => '/sign-up',
                'forgotpsw' => '/user-access/forgotPassword',
                'contact' => '/contact',
                'currency' => '/currency',
                'euroPlay' => '/euromillions/play',
                'euroFaq' => '/euromillions/faq',
                'euroHelp' => '/euromillions/help',
                'euroPastResult' => '/euromillions/results/draw-history-page',
                'euroResult' => '/euromillions/results',
                'legalAbout' => '/legal/about',
                'legalCookies' => '/legal/cookies',
                'legalIndex' => '/legal/index',
                'legalPrivacy' => '/legal/privacy',
                'christmasNumbersIndex' => '/christmas-lottery/results',
                'christmasNumbersSearch' => '/christmas-lottery/search',
            ],
            'ru' => [
                'index' => '/ru',
                'christmasPlay' => '/рождественская-лотерея/играть',
                'signin' => '/войти',
                'signup' => '/зарегистрироваться',
                'forgotpsw' => '/доступ-пользователей/Забыли-пароль',
                'contact' => '/написать-нам',
                'currency' => '/валюта',
                'euroPlay' => '/евромиллионы/играть',
                'euroFaq' => '/вопросы-и-ответы',
                'euroHelp' => '/евромиллионы/помощь',
                'euroPastResult' => '/евромиллионы/результаты/история-розыгрышей',
                'euroResult' => '/евромиллионы/результаты',
                'legalAbout' => '/юридическая-информация/о-нас',
                'legalCookies' => '/cookie-файлы',
                'legalIndex' => '/условия-использования',
                'legalPrivacy' => '/юридическая-информация/конфиденциальность',
                'christmasNumbersIndex' => '/ru/рождественская-лотерея/результаты',
                'christmasNumbersSearch' => '/ru/рождественская-лотерея/поиск',
            ],
            'es' => [
                'index' => '/es',
                'christmasPlay' => '/es/loteria-navidad/jugar',
                'signin' => '/es/ingreso',
                'signup' => '/es/registro',
                'forgotpsw' => '/es/recuperar-contrasena',
                'contact' => '/es/contacto',
                'currency' => '/es/moneda',
                'euroPlay' => '/es/euromillions/jugar',
                'euroFaq' => '/es/preguntas-frecuentes',
                'euroHelp' => '/es/euromillions/como-se-juega',
                'euroPastResult' => '/es/euromillions/resultados/sorteos-anteriores',
                'euroResult' => '/es/euromillions/resultados',
                'legalAbout' => '/es/quienes-somos',
                'legalCookies' => '/es/cookies  ',
                'legalIndex' => '/es/terminos-y-condiciones',
                'legalPrivacy' => '/es/privacidad',
                'christmasNumbersIndex' => '/es/loteria-navidad/resultados',
                'christmasNumbersSearch' => '/es/loteria-navidad/buscar',
            ],
            'it' => [
                'index' => '/it',
                'christmasPlay' => '/it/lotteria-natale/gioca',
                'signin' => '/it/accedi',
                'signup' => '/it/iscriviti',
                'forgotpsw' => '/it/recupero-password',
                'contact' => '/it/contatti',
                'currency' => '/it/valute',
                'euroPlay' => '/it/euromillions/gioca',
                'euroFaq' => '/it/faq',
                'euroHelp' => '/it/euromillions/come-giocare',
                'euroPastResult' => '/it/euromillions/estrazioni/archivio',
                'euroResult' => '/it/euromillions/estrazioni',
                'legalAbout' => '/it/chi-siamo',
                'legalCookies' => '/it/cookies',
                'legalIndex' => '/it/termini-e-condizioni',
                'legalPrivacy' => '/it/privacy-policy',
                'christmasNumbersIndex' => '/it/lotteria-natale/estrazioni',
                'christmasNumbersSearch' => '/christmas-lottery/search',
            ],
            'nl' => [
                'index' => '/nl',
                'christmasPlay' => '/nl/kerst-loterij/spelen',
                'signin' => '/nl/inloggen',
                'signup' => '/nl/registreren',
                'forgotpsw' => '/nl/wachtwoord-vergeten',
                'contact' => '/nl/contact',
                'currency' => '/nl/valuta',
                'euroPlay' => '/nl/euromillions/speel',
                'euroFaq' => '/nl/veel-gesteelde-vragen',
                'euroHelp' => '/nl/euromillions/speluitleg',
                'euroPastResult' => '/nl/euromillions/uitslagen/trekking-geschiedenis',
                'euroResult' => '/nl/euromillions/uitslagen',
                'legalAbout' => '/nl/over-ons',
                'legalCookies' => '/nl/cookies',
                'legalIndex' => '/nl/voorwaarden',
                'legalPrivacy' => '/nl/privacy',
                'christmasNumbersIndex' => '/nl/kerst-loterij/uitslagen',
                'christmasNumbersSearch' => '/christmas-lottery/search',
            ],

        ];
    }

}