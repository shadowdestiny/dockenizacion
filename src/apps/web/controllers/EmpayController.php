<?php


namespace EuroMillions\web\controllers;


class EmpayController extends PaymentController
{


    public function paymentAction()
    {
        $userId = $this->authService->getCurrentUser()->getId();
        $play_service = $this->domainServiceFactory->getPlayService();
        $result = $play_service->playWithEmPlay($userId);
        $this->playResult($result);
    }

}