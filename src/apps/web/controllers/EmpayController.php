<?php


namespace EuroMillions\web\controllers;


use EuroMillions\web\vo\EmPayCypher;

class EmpayController extends PaymentController
{


    public function paymentAction()
    {
        $di = \Phalcon\Di\FactoryDefault::getDefault();
        $config = $di->get('config')['empay_iframe'];
        $config = (array) $config;
        $params = $this->request->get();
        parse_str($params,$arr);
        if(EmPayCypher::paramAuthenticate($arr,$config['md5Key'])) {
            $userId = $this->authService->getCurrentUser()->getId();
            $play_service = $this->domainServiceFactory->getPlayService();
            $result = $play_service->playWithEmPlay($userId);
            $this->playResult($result);
        }
    }


}