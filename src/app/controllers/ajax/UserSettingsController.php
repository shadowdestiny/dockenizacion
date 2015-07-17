<?php
namespace EuroMillions\controllers\ajax;

use EuroMillions\services\preferences_strategies\WebCurrencyStrategy;

class UserSettingsController extends AjaxControllerBase
{
    public function setCurrencyAction($currency)
    {
        $user_service = $this->domainServiceFactory->getUserService();
        $user_service->setCurrency(new WebCurrencyStrategy(), new Currency($currency));
    }
}