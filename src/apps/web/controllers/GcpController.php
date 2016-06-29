<?php


namespace EuroMillions\web\controllers;


use EuroMillions\shared\vo\results\ActionResult;

class GcpController extends PaymentController
{


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


}