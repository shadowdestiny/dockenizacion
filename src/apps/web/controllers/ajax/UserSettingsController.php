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

    public function setLanguageAction($language)
    {
        $this->loadLanguage($language);
        echo json_encode(['result'=>'OK']);
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

}