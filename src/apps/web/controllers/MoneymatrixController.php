<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 19/08/18
 * Time: 14:53
 */

namespace EuroMillions\web\controllers;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\services\factories\DomainServiceFactory;

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
            $transactionID = $this->request->get('transactionID');
            $lotteryName = $this->request->get('lottery');
            $withWallet = $this->request->get('wallet');
            $userId = $this->authService->getCurrentUser()->getId();
            $play_service = $this->domainServiceFactory->getPlayService();
            $result = $play_service->playWithMoneyMatrix($lotteryName,$transactionID,$userId,$withWallet);
            $this->playResult($result);
        } catch (\Exception $e) {
            $this->playResult(new ActionResult(false));

        }
    }

}