<?php


namespace EuroMillions\web\controllers;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\GuestUser;
use EuroMillions\web\services\factories\DomainServiceFactory;
use Money\Currency;
use Money\Money;

class GcpController extends PaymentController
{


    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        /** @var DomainServiceFactory $domainServiceFactory */
        $domainServiceFactory = $dispatcher->getDI()->get('domainServiceFactory');
        $user_id = $domainServiceFactory->getAuthService()->getCurrentUser();
        $this->insertGoogleAnalyticsCodeViaEnvironment();
        if($user_id instanceof GuestUser) {
            $this->response->redirect('/sign-in');
            return false;
        } else {
            return true;
        }
    }


    public function resultAction()
    {
        $status = $this->request->getPost('orderStatus');
        if( (int)  $status == 1 ) {
            $userId = $this->authService->getCurrentUser()->getId();
            $play_service = $this->domainServiceFactory->getPlayService();
            $result = $play_service->playWithEmPlay($userId);
            $this->playResult($result);
        } else {
            $this->playResult(new ActionResult(false));
        }
    }

    public function depositAction()
    {
        $status = $this->request->getPost('orderStatus');
        if( (int)  $status != 1 ) {
            $this->response->redirect('/'.$this->lottery.'/result/failure');
            return false;
        }
        $amount = $this->request->getPost('orderAmount');
        $userId = $this->authService->getCurrentUser()->getId();
        $user = $this->userService->getUser($userId);
        $walletService = $this->domainServiceFactory->getWalletService();
        $amountParsed = new Money((int)str_replace('.', '', $amount), new Currency('EUR'));
        $result = $walletService->payFromEmpay($user, $amountParsed);
        if($result->success()) {
            $this->response->redirect('/account/wallet');
            return false;
        } else {
            $this->response->redirect('/'.$this->lottery.'/result/failure');
            return false;
        }
    }


}