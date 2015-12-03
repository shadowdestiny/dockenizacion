<?php
namespace EuroMillions\web\controllers\ajax;

use EuroMillions\web\entities\User;
use Money\Currency;

class UserSettingsController extends AjaxControllerBase
{
    public function setCurrencyAction($currency)
    {
        $user_service = $this->domainServiceFactory->getUserPreferencesService();
        $new_currency = new Currency($currency);
        $user_service->setCurrency($new_currency);
        $user = $this->domainServiceFactory->getAuthService()->getCurrentUser();
        if($user instanceof User) {
            $this->domainServiceFactory->getUserService()->updateCurrency($user,$new_currency);
        }
        echo json_encode(['result'=>'OK']);
    }
}