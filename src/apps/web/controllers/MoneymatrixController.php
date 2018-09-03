<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/08/18
 * Time: 14:53
 */

namespace EuroMillions\web\controllers;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\Currency;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\services\factories\DomainServiceFactory;
use Money\Money;

class MoneymatrixController extends PaymentController
{


    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
//        /** @var DomainServiceFactory $domainServiceFactory */
//        $domainServiceFactory = $dispatcher->getDI()->get('domainServiceFactory');
//        $user_id = $domainServiceFactory->getAuthService()->getCurrentUser();
//        $this->insertGoogleAnalyticsCodeViaEnvironment();
//        if($user_id instanceof GuestUser) {
//            $this->response->redirect('/sign-in');
//            return false;
//        } else {
//            return true;
//        }
    }


    public function successAction()
    {
        try {
            $this->playResult(new ActionResult(true));
        } catch (\Exception $e) {
            $this->playResult(new ActionResult(false));

        }
    }

}