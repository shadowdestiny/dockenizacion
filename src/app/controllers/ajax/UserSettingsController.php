<?php
namespace EuroMillions\controllers\ajax;

use EuroMillions\components\PhalconSessionWrapper;
use EuroMillions\services\preferences_strategies\WebCurrencyStrategy;
use Money\Currency;

class UserSettingsController extends AjaxControllerBase
{
    public function setCurrencyAction($currency)
    {
        $user_service = $this->domainServiceFactory->getUserService();
        $user_service->setCurrency(new WebCurrencyStrategy(new PhalconSessionWrapper()), new Currency($currency));
        echo json_encode(['result'=>'OK']);
    }
}