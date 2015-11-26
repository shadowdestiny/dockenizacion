<?php
namespace EuroMillions\web\controllers\ajax;

use Money\Currency;

class UserSettingsController extends AjaxControllerBase
{
    public function setCurrencyAction($currency)
    {
        $user_service = $this->domainServiceFactory->getUserPreferencesService();
        $user_service->setCurrency(new Currency($currency));
        echo json_encode(['result'=>'OK']);
    }
}